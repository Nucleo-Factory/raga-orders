<?php

namespace RagaOrders\POConfirmation\Livewire;

use Livewire\Component;
use RagaOrders\POConfirmation\Services\POConfirmationService;

class POConfirmationManager extends Component
{
    public $isEnabled;
    public $statistics = [];
    public $processing = false;
    public $message = '';
    public $messageType = '';

    protected $service;

    public function boot(POConfirmationService $service)
    {
        $this->service = $service;
    }

    public function mount()
    {
        $this->isEnabled = config('po-confirmation.enabled', false);
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->statistics = $this->service->getStatistics();
    }

    public function toggleModule()
    {
        $this->isEnabled = !$this->isEnabled;

        // Update .env file
        $this->updateEnvironmentVariable('PO_CONFIRMATION_ENABLED', $this->isEnabled ? 'true' : 'false');

        $this->message = $this->isEnabled ? 'Módulo activado' : 'Módulo desactivado';
        $this->messageType = 'success';

        $this->loadStatistics();
    }

    public function processPendingPOs()
    {
        $this->processing = true;

        try {
            $results = $this->service->processPendingPOs();

            $this->message = "Procesamiento completado. {$results['processed']} POs procesadas, {$results['emails_sent']} emails enviados.";
            $this->messageType = 'success';

            if (!empty($results['errors'])) {
                $this->message .= ' Se encontraron algunos errores.';
                $this->messageType = 'warning';
            }

        } catch (\Exception $e) {
            $this->message = 'Error al procesar POs: ' . $e->getMessage();
            $this->messageType = 'error';
        }

        $this->processing = false;
        $this->loadStatistics();
    }

    public function cleanExpiredHashes()
    {
        try {
            $cleaned = $this->service->cleanExpiredHashes();

            $this->message = "Limpieza completada. {$cleaned} hashes expirados eliminados.";
            $this->messageType = 'success';

        } catch (\Exception $e) {
            $this->message = 'Error al limpiar hashes: ' . $e->getMessage();
            $this->messageType = 'error';
        }

        $this->loadStatistics();
    }

    protected function updateEnvironmentVariable($key, $value)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        if (str_contains($envContent, $key)) {
            $envContent = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $envContent);
        } else {
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $envContent);
    }

    public function render()
    {
        return view('po-confirmation::components.manager');
    }
}
