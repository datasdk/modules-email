<?php

namespace Modules\Email\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Email\Models\Email;
use Modules\Email\Services\EmailDeliveryService;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Carbon\Carbon;
use Throwable;

class ProcessEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    public $tries = 3;
    public $maxExceptions = 3;
    public $backoff = [60, 300, 600];
    public $failOnTimeout = true;

    protected Email $email;

    public function __construct(Email $email)
    {
       
        $this->email = $email;
    }

    public function handle(): void
    {
       
        try {
            // Reload the email to ensure we have fresh data
            $email = $this->email->fresh();
            
            if (!$email) {
                throw new \Exception("Email model not found for ID: " . $this->email->id);
            }

            // Check if email is already sent or cancelled
            if ($email->sent || $email->status === 'cancelled') {
           
                return;
            }

            // Check send_after
            if ($email->send_after && Carbon::parse($email->send_after)->gt(now())) {
                $delayInSeconds = Carbon::parse($email->send_after)->diffInSeconds(now());
           
                $this->release($delayInSeconds);
                return;
            }
            

       
            
            // Send email using service
            $emailService = app(EmailDeliveryService::class);

            $result = $emailService->send($email);
            
          
        
        } catch (Throwable $exception) {

            $email->addError($exception->getMessage());

            Log::error("Email job failed for ID {$this->email->id}: " . $exception->getMessage(), [
                'exception' => $exception,
                'trace' => $exception->getTraceAsString()
            ]);
            
            $this->fail($exception);
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error("Email job permanently failed for ID {$this->email->id}: " . $exception->getMessage());
        
        // Update email status to failed
        if ($this->email) {
            $this->email->update([
                'status' => 'failed',
                'last_error' => $exception->getMessage()
            ]);
        }
    }

    /*
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->email->id))
                ->expireAfter(300) // Release lock after 5 minutes
                ->dontRelease()    // Don't release job back to queue on overlap
        ];
    }
        */
}