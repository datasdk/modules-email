<?php

namespace Modules\Email\Console\Commands;

use Illuminate\Console\Command;
use Modules\Email\Models\Email;
use Modules\Email\Jobs\ProcessEmailJob;
use Illuminate\Support\Facades\Log;

class SendEmailsComand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch all scheduled emails to the queue';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Fetching scheduled emails...");

        $emails = Email::query()
            ->where('send_after', '<=', now())
            ->whereNull('sent')
            ->whereNull('deleted_at')
            ->where('status', 'scheduled')
            ->get();



        $count = $emails->count();

        if ($count === 0) {
            $this->info("No scheduled emails found.");
            return;
        }

        $this->info("Found {$count} scheduled emails. Dispatching to queue...");

        $dispatchedCount = 0;
        $failedCount = 0;

        foreach ($emails as $email) {
            try {
                // Brug dispatch() i stedet for dispatchSync() for at sende til kø
                ProcessEmailJob::dispatch($email);
                $dispatchedCount++;
                $this->info("✓ Dispatched email ID {$email->id}");
                
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to dispatch email ID {$email->id}: " . $e->getMessage());
                $this->error("✗ Failed to dispatch email ID {$email->id}: " . $e->getMessage());
            }
        }

        $this->info("Dispatched {$dispatchedCount} emails. Failed: {$failedCount}");
    }
}