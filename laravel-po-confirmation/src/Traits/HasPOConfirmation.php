<?php

namespace RagaOrders\POConfirmation\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasPOConfirmation
{
    /**
     * Generate a secure confirmation hash for the PO.
     */
    public function generateConfirmationHash(): string
    {
        $hash = Str::random(64);
        $expiresAt = Carbon::now()->addHours((int) config('po-confirmation.hash_expiry_hours', 72));

        $this->update([
            'confirmation_hash' => $hash,
            'hash_expires_at' => $expiresAt,
        ]);

        return $hash;
    }

    /**
     * Check if the provided hash is valid for this PO.
     */
    public function isConfirmationHashValid(string $hash): bool
    {
        return $this->confirmation_hash === $hash
            && $this->hash_expires_at
            && $this->hash_expires_at->isFuture();
    }

    /**
     * Confirm the PO and optionally update delivery date.
     */
    public function confirmPO(?string $newDeliveryDate = null): bool
    {
        if ($newDeliveryDate) {
            $this->update([
                'update_date_po' => $newDeliveryDate,
                'confirm_update_date_po' => true,
            ]);
        }

        // Clear the hash after confirmation
        $this->update([
            'confirmation_hash' => null,
            'hash_expires_at' => null,
        ]);

        return true;
    }

    /**
     * Update the delivery date for the PO.
     */
    public function updateDeliveryDate(string $newDate): bool
    {
        return $this->update([
            'update_date_po' => $newDate,
            'confirm_update_date_po' => true,
        ]);
    }

    /**
     * Mark email as sent.
     */
    public function markEmailAsSent(): bool
    {
        return $this->update([
            'confirmation_email_sent' => true,
            'confirmation_email_sent_at' => Carbon::now(),
        ]);
    }

    /**
     * Scope para POs que necesitan confirmación
     */
    public function scopePendingConfirmation($query)
    {
        $daysBeforeConfirmation = $this->getTimingSetting('days_before_confirmation', 5);
        $confirmationDate = now()->addDays($daysBeforeConfirmation);

        return $query->where('status', 'pending')
                    ->where('confirmation_email_sent', false)
                    ->where('date_required_in_destination', '<=', $confirmationDate)
                    ->where(function($q) {
                        $q->whereNull('confirmation_hash')
                          ->orWhere('hash_expires_at', '<', now());
                    });
    }

    /**
     * Scope to get POs with valid confirmation hashes.
     */
    public function scopeWithValidHash(Builder $query): Builder
    {
        return $query->whereNotNull('confirmation_hash')
                    ->where('hash_expires_at', '>', Carbon::now());
    }

    /**
     * Scope to get POs with expired hashes.
     */
    public function scopeWithExpiredHash(Builder $query): Builder
    {
        return $query->whereNotNull('confirmation_hash')
                    ->where('hash_expires_at', '<=', Carbon::now());
    }

    /**
     * Get the confirmation URL for this PO.
     */
    public function getConfirmationUrl(): string
    {
        if (!$this->confirmation_hash) {
            return '';
        }

        return route('po.confirm', ['hash' => $this->confirmation_hash]);
    }

    /**
     * Check if the PO can be confirmed.
     */
    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending' && $this->confirmation_hash;
    }

    /**
     * Check if the PO has been confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->confirm_update_date_po || $this->update_date_po;
    }

    /**
     * Get the formatted delivery date.
     */
    public function getFormattedDeliveryDateAttribute(): string
    {
        if ($this->update_date_po) {
            return $this->update_date_po->format('d/m/Y');
        }

        if ($this->date_required_in_destination) {
            return $this->date_required_in_destination->format('d/m/Y');
        }

        return 'No especificada';
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
}
