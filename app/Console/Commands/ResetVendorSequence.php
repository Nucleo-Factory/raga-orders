<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetVendorSequence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:reset-sequence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the auto-increment sequence for vendors table';

    /**
     * Execute the console command.
     */
        public function handle()
    {
        try {
            // Get the current max ID
            $maxId = DB::table('vendors')->max('id') ?? 0;

            $this->info("Current max ID in vendors table: " . $maxId);

            // Use the known sequence name
            $sequenceName = 'vendors_id_seq';

            $this->info("Sequence name: " . $sequenceName);

            // Get current sequence value
            $currentSequence = DB::select("SELECT last_value FROM {$sequenceName}")[0]->last_value ?? 0;
            $this->info("Current sequence value: " . $currentSequence);

            // Reset the sequence to the max ID
            DB::statement("SELECT setval('{$sequenceName}', {$maxId}, false)");

            // Verify the new sequence value
            $newSequence = DB::select("SELECT last_value FROM {$sequenceName}")[0]->last_value ?? 0;
            $this->info("New sequence value: " . $newSequence);
            $this->info("Next ID will be: " . ($newSequence + 1));

            $this->info("Vendor sequence reset successfully!");

        } catch (\Exception $e) {
            $this->error("Error resetting sequence: " . $e->getMessage());
        }
    }
}
