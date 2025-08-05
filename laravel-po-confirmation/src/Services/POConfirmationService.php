<?php

namespace RagaOrders\POConfirmation\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RagaOrders\POConfirmation\Mail\POConfirmationMail;
use RagaOrders\POConfirmation\Mail\POConfirmedMail;

class POConfirmationService
{
    /**
     * Process all pending POs and send confirmation emails.
     */
    public function processPendingPOs(): array
    {
        $results = [
            'processed' => 0,
            'emails_sent' => 0,
            'errors' => [],
        ];

        try {
            $poModel = config('po-confirmation.purchase_order_model');
            $purchaseOrders = $poModel::pendingConfirmation()->get();

            foreach ($purchaseOrders as $po) {
                try {
                    $this->processSinglePO($po);
                    $results['processed']++;
                    $results['emails_sent']++;
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'po_id' => $po->id,
                        'error' => $e->getMessage(),
                    ];
                    Log::error("Error processing PO {$po->id}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $results['errors'][] = [
                'error' => 'General error: ' . $e->getMessage(),
            ];
            Log::error("Error in processPendingPOs: " . $e->getMessage());
        }

        return $results;
    }

    /**
     * Process a single PO and send confirmation email.
     */
    public function processSinglePO($po): bool
    {
        // Generate confirmation hash
        $hash = $po->generateConfirmationHash();

        // Send confirmation email
        $this->sendConfirmationEmail($po);

        // Mark email as sent
        $po->markEmailAsSent();

        Log::info("PO confirmation email sent for PO {$po->order_number}");

        return true;
    }

    /**
     * Send confirmation email for a PO.
     */
    public function sendConfirmationEmail($po): bool
    {
        try {
            if (!$po->vendor || !$po->vendor->email) {
                throw new \Exception("Vendor not found or no email available for PO {$po->id}");
            }

            // Usar configuraciones dinámicas
            $subject = $this->getEmailSetting('email_subject', 'Confirmación de Orden de Compra - {order_number}');
            $subject = str_replace('{order_number}', $po->order_number, $subject);

            Mail::to($po->vendor->email)
                ->send(new \RagaOrders\POConfirmation\Mail\POConfirmationMail($po));

            $po->markEmailAsSent();

            return true;
        } catch (\Exception $e) {
            \Log::error("Error sending confirmation email for PO {$po->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Confirm a PO by hash and optionally update delivery date.
     */
    public function confirmPOByHash(string $hash, ?string $newDeliveryDate = null): array
    {
        $poModel = config('po-confirmation.purchase_order_model');
        $po = $poModel::where('confirmation_hash', $hash)->first();

        if (!$po) {
            return [
                'success' => false,
                'message' => 'Orden de compra no encontrada.',
            ];
        }

        if (!$po->isConfirmationHashValid($hash)) {
            return [
                'success' => false,
                'message' => 'El enlace de confirmación ha expirado o es inválido.',
            ];
        }

        try {
            $po->confirmPO($newDeliveryDate);

            // Send confirmation notification to admin if enabled
            if (config('po-confirmation.notify_admin_on_confirmation', true)) {
                $this->notifyAdminOfConfirmation($po);
            }

            return [
                'success' => true,
                'message' => 'Orden de compra confirmada exitosamente.',
                'po' => $po,
            ];
        } catch (\Exception $e) {
            Log::error("Error confirming PO {$po->id}: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error al confirmar la orden de compra.',
            ];
        }
    }

    /**
     * Clean expired hashes from the database.
     */
    public function cleanExpiredHashes(): int
    {
        $poModel = config('po-confirmation.purchase_order_model');

        $expiredPOs = $poModel::withExpiredHash()->get();
        $cleaned = 0;

        foreach ($expiredPOs as $po) {
            $po->update([
                'confirmation_hash' => null,
                'hash_expires_at' => null,
            ]);
            $cleaned++;
        }

        Log::info("Cleaned {$cleaned} expired confirmation hashes");

        return $cleaned;
    }

    /**
     * Get statistics for the PO confirmation module.
     */
    public function getStatistics(): array
    {
        $poModel = config('po-confirmation.purchase_order_model');

        return [
            'pending_confirmation' => $poModel::pendingConfirmation()->count(),
            'emails_sent' => $poModel::where('confirmation_email_sent', true)->count(),
            'confirmed' => $poModel::where('confirm_update_date_po', true)->count(),
            'with_valid_hash' => $poModel::withValidHash()->count(),
            'with_expired_hash' => $poModel::withExpiredHash()->count(),
        ];
    }

    /**
     * Notify admin of PO confirmation.
     */
    protected function notifyAdminOfConfirmation($po): void
    {
        try {
            $adminEmail = config('po-confirmation.admin_email');

            if ($adminEmail) {
                Mail::to($adminEmail)
                    ->send(new POConfirmedMail($po));
            }
        } catch (\Exception $e) {
            Log::error("Error notifying admin of PO confirmation: " . $e->getMessage());
        }
    }

    /**
     * Validate delivery date format.
     */
    public function validateDeliveryDate(string $date): bool
    {
        try {
            $parsedDate = \Carbon\Carbon::parse($date);
            return $parsedDate->isFuture();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get PO by hash.
     */
    public function getPOByHash(string $hash)
    {
        $poModel = config('po-confirmation.purchase_order_model');
        return $poModel::where('confirmation_hash', $hash)->first();
    }

    /**
     * Get email setting value
     */
    private function getEmailSetting(string $key, string $default = ''): string
    {
        if (class_exists('RagaOrders\POConfirmation\Models\POConfirmationSetting')) {
            return \RagaOrders\POConfirmation\Models\POConfirmationSetting::getValue($key, $default);
        }
        return $default;
    }

    /**
     * Get general setting value
     */
    private function getGeneralSetting(string $key, string $default = ''): string
    {
        if (class_exists('RagaOrders\POConfirmation\Models\POConfirmationSetting')) {
            return \RagaOrders\POConfirmation\Models\POConfirmationSetting::getValue($key, $default);
        }
        return $default;
    }

    /**
     * Get timing setting value
     */
    private function getTimingSetting(string $key, int $default = 5): int
    {
        if (class_exists('RagaOrders\POConfirmation\Models\POConfirmationSetting')) {
            return \RagaOrders\POConfirmation\Models\POConfirmationSetting::getValue($key, $default);
        }
        return $default;
    }
}
