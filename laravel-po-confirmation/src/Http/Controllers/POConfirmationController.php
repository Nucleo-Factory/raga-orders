<?php

namespace RagaOrders\POConfirmation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RagaOrders\POConfirmation\Services\POConfirmationService;

class POConfirmationController extends Controller
{
    protected $service;

    public function __construct(POConfirmationService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the confirmation form.
     */
    public function show(string $hash)
    {
        $po = $this->service->getPOByHash($hash);

        if (!$po) {
            return view('po-confirmation.error', [
                'message' => $this->getGeneralSetting('not_found_message', 'Orden de compra no encontrada.'),
                'title' => 'Error'
            ]);
        }

        if (!$po->isConfirmationHashValid($hash)) {
            return view('po-confirmation.error', [
                'message' => $this->getGeneralSetting('expired_message', 'El enlace de confirmación ha expirado o es inválido.'),
                'title' => 'Enlace Expirado'
            ]);
        }

        return view('po-confirmation.confirm', [
            'po' => $po,
            'hash' => $hash
        ]);
    }

    /**
     * Process the confirmation.
     */
    public function confirm(Request $request, string $hash)
    {
        $request->validate([
            'new_delivery_date' => 'nullable|date|after:today',
        ], [
            'new_delivery_date.after' => 'La fecha de entrega debe ser posterior a hoy.',
        ]);

        $newDeliveryDate = $request->input('new_delivery_date');

        $result = $this->service->confirmPOByHash($hash, $newDeliveryDate);

        if ($result['success']) {
            return view('po-confirmation.success', [
                'po' => $result['po'],
                'message' => $this->getGeneralSetting('success_message', '¡Orden de compra confirmada exitosamente!')
            ]);
        }

        return view('po-confirmation.error', [
            'message' => $result['message'],
            'title' => 'Error de Confirmación'
        ]);
    }

    /**
     * Show confirmation success page.
     */
    public function success()
    {
        return view('po-confirmation.success');
    }

    /**
     * Show error page.
     */
    public function error()
    {
        return view('po-confirmation.error');
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
