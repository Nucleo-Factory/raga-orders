<?php

namespace RagaOrders\POConfirmation\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'po-confirmation:uninstall {--force : Forzar desinstalación sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desinstalar el módulo de confirmación de POs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres desinstalar el módulo de confirmación de POs? Esta acción no se puede deshacer.')) {
                $this->info('Desinstalación cancelada.');
                return 0;
            }
        }

        $this->info('Desinstalando módulo de confirmación de POs...');

        try {
            // Rollback migrations
            $this->info('Revirtiendo migraciones...');
            Artisan::call('migrate:rollback', [
                '--path' => 'vendor/raga-orders/laravel-po-confirmation/database/migrations',
                '--force' => true
            ]);

            // Remove configuration file
            $this->info('Eliminando archivo de configuración...');
            $this->removeConfig();

            // Remove environment variables
            $this->info('Eliminando variables de entorno...');
            $this->removeEnvironmentVariables();

            $this->info('✅ Módulo desinstalado exitosamente!');
            $this->info('');
            $this->info('Recuerda remover el trait HasPOConfirmation de tu modelo PurchaseOrder si lo agregaste.');

        } catch (\Exception $e) {
            $this->error('Error durante la desinstalación: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Remove the configuration file.
     */
    protected function removeConfig()
    {
        $configPath = base_path('config/po-confirmation.php');

        if (file_exists($configPath)) {
            unlink($configPath);
        }
    }

    /**
     * Remove environment variables from .env file.
     */
    protected function removeEnvironmentVariables()
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        $variables = [
            'PO_CONFIRMATION_ENABLED',
            'PO_CONFIRMATION_HASH_EXPIRY',
            'PO_CONFIRMATION_FROM_NAME',
            'PO_CONFIRMATION_FROM_ADDRESS',
            'PO_CONFIRMATION_AUTO_SEND',
            'PO_CONFIRMATION_CHECK_INTERVAL',
            'PO_CONFIRMATION_NOTIFY_ADMIN',
            'PO_CONFIRMATION_ADMIN_EMAIL',
        ];

        foreach ($variables as $variable) {
            $envContent = preg_replace("/^{$variable}=.*$/m", '', $envContent);
        }

        // Remove empty lines
        $envContent = preg_replace('/^\s*[\r\n]+/m', '', $envContent);

        file_put_contents($envPath, $envContent);
    }
}
