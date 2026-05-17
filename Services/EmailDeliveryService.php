<?php

namespace Modules\Email\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Modules\Email\Models\Email;
use Modules\Email\Mail\Standard as StandardMailable;
use Throwable;

class EmailDeliveryService
{
    /**
     * Sender e-mailen og opdaterer status på Email-modellen.
     */
    public function send(Email $email): Email
    {
        if (app()->environment('testing')) {
            return $email;
        }

        $this->resetErrors($email);
        
        $errors = [];

        try {
            // Valider at e-mail-adressen er gyldig
            if (!filter_var($email->to, FILTER_VALIDATE_EMAIL)) {
                $errors[] = $this->cleanString("Ugyldig e-mail-adresse: {$email->to}");
            }

            if (!$email->canSend()) {
                $errors[] = $this->cleanString("E-mail opfylder ikke krav for at blive sendt");
            }

            $templateIsActive = boolval($email->template->active ?? false);
            if (!$templateIsActive) {
                $errors[] = $this->cleanString("Mail-skabelon er inaktiv");
            }

            $smtpActive = boolval(config('mail.mailers.smtp.active') ?? false);
            if (!$smtpActive) {
                $errors[] = $this->cleanString("Mailserveren er inaktiv");
            }

            // Kun send, hvis der ingen fejl er
            if (empty($errors)) {

                Mail::to($email->to)->send(new StandardMailable($email));
            }

            
        } catch (Throwable $ex) {



            $errorMessage = $this->cleanString($ex->getMessage());
            $errors[] = $errorMessage;


            // Log alligevel
            Log::alert('EmailDeliveryService: Fejl ved afsendelse af email.', [
                'error'    => $errorMessage,
                'file'     => $ex->getFile(),
                'line'     => $ex->getLine(),
                'email_id' => $email->id,
                'email_to' => $email->to,
                'env'      => app()->environment(),
            ]);
        }



        // Tilføj alle fejl til email
        if (empty($errors)) {

            // Opdater status til sendt kun hvis alt lykkedes
            $this->markAsSent($email);
                       
        } else {

            $this->addError($email, $errors);
        }

        return $email;
    }
    
    /**
     * Clean string for UTF-8 encoding
     */
    protected function cleanString(string $string): string
    {
        // Convert to UTF-8 using mb_convert_encoding
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        
        // Trim and return
        return trim($string);
    }
    
    /**
     * Add errors to email model
     */
    protected function addError(Email $email, array $errors): void
    {
        // Clean all error messages
        $cleanedErrors = array_map([$this, 'cleanString'], $errors);
        
        // Remove duplicates
        $cleanedErrors = array_unique($cleanedErrors);
        
        // Limit error length to prevent database issues
        foreach ($cleanedErrors as &$error) {
            if (strlen($error) > 500) {
                $error = substr($error, 0, 497) . '...';
            }
        }
        
        // Update email with errors
        $email->update([
            'errors' => json_encode($cleanedErrors, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE),
            'last_error' => $this->cleanString(implode('; ', array_slice($cleanedErrors, 0, 3))),
            'status' => 'failed',
            'last_attempt' => now()
        ]);
    }
    
    /**
     * Reset errors on email
     */
    protected function resetErrors(Email $email): void
    {
        $email->update([
            'errors' => null,
            'last_error' => null
        ]);
    }
    
    /**
     * Mark email as sent
     */
    protected function markAsSent(Email $email): void
    {
        $email->update([
            'sent' => now(),
            'status' => 'sent',
            'errors' => null,
            'last_error' => null,
            'last_attempt' => now()
        ]);
    }
    
    /**
     * Determine if errors are temporary and should trigger a retry
     */
    public function isRetryableError(array $errors): bool
    {
        $temporaryErrors = [
            'connection',
            'timeout',
            'temporarily',
            'rate limit',
            'quota',
            'too many',
            'unavailable',
            'network',
            'smtp',
            'connection refused'
        ];
        
        foreach ($errors as $error) {
            $errorLower = strtolower($this->cleanString($error));
            foreach ($temporaryErrors as $tempError) {
                if (str_contains($errorLower, $tempError)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}