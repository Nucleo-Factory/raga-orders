<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetAllSequences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sequences:reset-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all auto-increment sequences for main tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = [
            'vendors',
            'ship_tos',
            'bill_tos',
            'products',
            'purchase_orders',
            'shipping_documents',
            'companies',
            'users',
            'hubs',
            'kanban_boards',
            'kanban_statuses',
            'authorizations',
            'notifications',
            'forecasts'
        ];

        $this->info("Starting to reset sequences for all tables...");
        $this->newLine();

        foreach ($tables as $table) {
            try {
                // Check if table exists
                $tableExists = DB::select("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = ?)", [$table])[0]->exists ?? false;

                if (!$tableExists) {
                    $this->warn("Table '{$table}' does not exist, skipping...");
                    continue;
                }

                // Get the current max ID
                $maxId = DB::table($table)->max('id') ?? 0;

                // Get the sequence name
                $sequenceName = $table . '_id_seq';

                // Check if sequence exists
                $sequenceExists = DB::select("SELECT EXISTS (SELECT FROM information_schema.sequences WHERE sequence_name = ?)", [$sequenceName])[0]->exists ?? false;

                if (!$sequenceExists) {
                    $this->warn("Sequence '{$sequenceName}' does not exist, skipping...");
                    continue;
                }

                // Get current sequence value
                $currentSequence = DB::select("SELECT last_value FROM {$sequenceName}")[0]->last_value ?? 0;

                // Reset the sequence to the max ID
                DB::statement("SELECT setval('{$sequenceName}', {$maxId}, false)");

                // Verify the new sequence value
                $newSequence = DB::select("SELECT last_value FROM {$sequenceName}")[0]->last_value ?? 0;

                $this->info("✓ {$table}: max ID = {$maxId}, sequence reset from {$currentSequence} to {$newSequence}");

            } catch (\Exception $e) {
                $this->error("✗ Error with table '{$table}': " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("All sequences reset completed!");
    }
}
