<?php

namespace App\Console\Commands;

use App\Models\PurchaseOrder;
use App\Services\Ship24TrackerService;
use Illuminate\Console\Command;

class MigrateExistingPurchaseOrdersToShip24 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ship24:migrate-existing-pos 
                            {--limit=50 : Número máximo de POs a procesar} 
                            {--dry-run : Solo mostrar qué se haría sin ejecutar} 
                            {--force : Forzar creación incluso si ya existe tracker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrar Purchase Orders existentes con tracking_id a Ship24 per-shipment';

    /**
     * Execute the console command.
     */
    public function handle(Ship24TrackerService $trackerService): int
    {
        $limit = $this->option('limit');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info("🚀 Iniciando migración de POs existentes a Ship24 per-shipment");
        $this->info("Límite: {$limit} | Dry Run: " . ($dryRun ? 'SÍ' : 'NO') . " | Force: " . ($force ? 'SÍ' : 'NO'));
        $this->newLine();

        // Obtener POs con tracking_id que no tienen trackers Ship24
        $query = PurchaseOrder::whereNotNull('tracking_id')
            ->where('tracking_id', '!=', '');

        if (!$force) {
            $query->whereDoesntHave('ship24Tracker', function($q) {
                $q->where('status', 'active');
            });
        }

        $purchaseOrders = $query->limit($limit)->get();

        if ($purchaseOrders->isEmpty()) {
            $this->warn('⚠️  No se encontraron Purchase Orders para migrar');
            return 0;
        }

        $this->info("📦 Encontradas {$purchaseOrders->count()} Purchase Orders para migrar:");
        $this->newLine();

        $bar = $this->output->createProgressBar($purchaseOrders->count());
        $bar->start();

        $results = [
            'success' => 0,
            'skipped' => 0,
            'errors' => 0,
        ];

        foreach ($purchaseOrders as $po) {
            $bar->advance();
            
            try {
                $this->newLine();
                $this->line("📋 PO #{$po->id} - {$po->order_number} - Tracking: {$po->tracking_id}");

                if ($dryRun) {
                    $this->line("   [DRY RUN] Se crearía tracker para esta PO");
                    $results['success']++;
                    continue;
                }

                // Verificar si ya existe tracker activo
                $existingTracker = $po->ship24Tracker()
                    ->where('tracking_number', $po->tracking_id)
                    ->where('status', 'active')
                    ->first();

                if ($existingTracker && !$force) {
                    $this->line("   ⏭️  Ya existe tracker activo, saltando...");
                    $results['skipped']++;
                    continue;
                }

                // Crear tracker
                $tracker = $trackerService->createTrackerForPurchaseOrder($po);

                if ($tracker) {
                    $this->line("   ✅ Tracker creado: ID {$tracker->id} | Ship24 ID: {$tracker->ship24_tracker_id}");
                    $results['success']++;
                } else {
                    $this->line("   ❌ Error: No se pudo crear el tracker");
                    $results['errors']++;
                }

            } catch (\Exception $e) {
                $this->line("   💥 Excepción: {$e->getMessage()}");
                $results['errors']++;
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Mostrar resumen
        $this->info("📊 Resumen de migración:");
        $this->table(
            ['Estado', 'Cantidad'],
            [
                ['✅ Exitosos', $results['success']],
                ['⏭️  Saltados', $results['skipped']],
                ['❌ Errores', $results['errors']],
                ['📦 Total', $purchaseOrders->count()],
            ]
        );

        if ($dryRun) {
            $this->warn("⚠️  Esto fue un DRY RUN - No se realizaron cambios reales");
            $this->info("💡 Ejecuta sin --dry-run para aplicar los cambios");
        }

        return $results['errors'] > 0 ? 1 : 0;
    }
}