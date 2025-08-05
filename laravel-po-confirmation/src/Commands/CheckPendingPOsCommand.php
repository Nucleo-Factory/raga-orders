<?php

namespace RagaOrders\POConfirmation\Commands;

use Illuminate\Console\Command;
use RagaOrders\POConfirmation\Services\POConfirmationService;

class CheckPendingPOsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'po:check-pending {--clean : Limpiar hashes expirados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesar POs pendientes de confirmación';

    /**
     * Execute the console command.
     */
    public function handle(POConfirmationService $service)
    {
        if (!$this->option('clean')) {
            $this->info('Procesando POs pendientes de confirmación...');

            $results = $service->processPendingPOs();

            $this->info("✅ Procesamiento completado:");
            $this->info("- POs procesadas: {$results['processed']}");
            $this->info("- Emails enviados: {$results['emails_sent']}");

            if (!empty($results['errors'])) {
                $this->warn("⚠️ Errores encontrados:");
                foreach ($results['errors'] as $error) {
                    $this->error("- " . ($error['po_id'] ?? '') . ": " . $error['error']);
                }
            }
        } else {
            $this->info('Limpiando hashes expirados...');

            $cleaned = $service->cleanExpiredHashes();

            $this->info("✅ Limpieza completada: {$cleaned} hashes expirados eliminados");
        }

        // Show statistics
        $stats = $service->getStatistics();
        $this->info('');
        $this->info('📊 Estadísticas del módulo:');
        $this->info("- POs pendientes de confirmación: {$stats['pending_confirmation']}");
        $this->info("- Emails enviados: {$stats['emails_sent']}");
        $this->info("- POs confirmadas: {$stats['confirmed']}");
        $this->info("- Con hash válido: {$stats['with_valid_hash']}");
        $this->info("- Con hash expirado: {$stats['with_expired_hash']}");

        return 0;
    }
}
