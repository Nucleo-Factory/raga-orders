<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetTableSequence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:reset-sequence {table : The table name to reset sequence}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the auto-increment sequence for any table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('table');

        try {
            // Check if table exists
            $tableExists = DB::select("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = ?)", [$tableName])[0]->exists ?? false;

            if (!$tableExists) {
                $this->error("Table '{$tableName}' does not exist");
                return;
            }

            // Get the current max ID
            $maxId = DB::table($tableName)->max('id') ?? 0;

            $this->info("Current max ID in {$tableName} table: " . $maxId);

            // Get the sequence name
            $sequenceName = $tableName . '_id_seq';

            $this->info("Sequence name: " . $sequenceName);

            // Check if sequence exists
            $sequenceExists = DB::select("SELECT EXISTS (SELECT FROM information_schema.sequences WHERE sequence_name = ?)", [$sequenceName])[0]->exists ?? false;

            if (!$sequenceExists) {
                $this->error("Sequence '{$sequenceName}' does not exist");
                return;
            }

            // Get current sequence value
            $currentSequence = DB::select("SELECT last_value FROM {$sequenceName}")[0]->last_value ?? 0;
            $this->info("Current sequence value: " . $currentSequence);

            // Reset the sequence to the max ID
            DB::statement("SELECT setval('{$sequenceName}', {$maxId}, false)");

            // Verify the new sequence value
            $newSequence = DB::select("SELECT last_value FROM {$sequenceName}")[0]->last_value ?? 0;
            $this->info("New sequence value: " . $newSequence);
            $this->info("Next ID will be: " . ($newSequence + 1));

            $this->info("Sequence for table '{$tableName}' reset successfully!");

        } catch (\Exception $e) {
            $this->error("Error resetting sequence: " . $e->getMessage());
        }
    }
}
