<?php

namespace RagaOrders\POConfirmation\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'po-confirmation:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instalar el módulo de confirmación de POs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Instalando módulo de Confirmación de PO...');

        try {
            // Run migrations
            $this->info('Ejecutando migraciones...');
            $this->call('migrate', ['--path' => 'vendor/raga-orders/laravel-po-confirmation/database/migrations']);

            // Publish configuration
            $this->info('Publicando configuración...');
            $this->call('vendor:publish', [
                '--provider' => 'RagaOrders\POConfirmation\POConfirmationServiceProvider',
                '--tag' => 'config'
            ]);

            // Add environment variables
            $this->addEnvironmentVariables();

            $this->info('✅ Módulo instalado correctamente!');
            $this->info('');
            $this->info('Para activar el módulo, agrega a tu .env:');
            $this->info('PO_CONFIRMATION_ENABLED=true');
            $this->info('');
            $this->info('Para configurar el módulo, visita: /settings/po-confirmation');

        } catch (\Exception $e) {
            $this->error('Error durante la instalación: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Publish the configuration file.
     */
    protected function publishConfig()
    {
        $configPath = base_path('config/po-confirmation.php');

        if (!file_exists($configPath)) {
            copy(
                __DIR__ . '/../../config/po-confirmation.php',
                $configPath
            );
        }
    }

    /**
     * Add environment variables to .env file.
     */
    protected function addEnvironmentVariables()
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        $variables = [
            'PO_CONFIRMATION_ENABLED=false',
            'PO_CONFIRMATION_HASH_EXPIRY=72',
            'PO_CONFIRMATION_FROM_NAME="Raga Orders"',
            'PO_CONFIRMATION_FROM_ADDRESS=noreply@ragaorders.com',
            'PO_CONFIRMATION_AUTO_SEND=true',
            'PO_CONFIRMATION_CHECK_INTERVAL=hourly',
            'PO_CONFIRMATION_NOTIFY_ADMIN=true',
            'PO_CONFIRMATION_ADMIN_EMAIL=admin@ragaorders.com',
        ];

        foreach ($variables as $variable) {
            if (!str_contains($envContent, explode('=', $variable)[0])) {
                $envContent .= "\n" . $variable;
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
