<?php

namespace Modules\Email\Services\Backup;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;
use App\Mail\Standard;

class EmailService
{
    /**
     * Sends an email with the provided data.
     *
     * @param array $data Email data including recipient, subject, message, and attachments
     * @return bool|null Returns true if the email is sent successfully, null if there was an error
     */
    public function send(array $data)
    {

        // Get recipient, subject, and message; avoid errors if they are missing
        $to = $data["to"] ?? null;
        $attachments = $data["attachments"] ?? [];


        if(isset($data["template"])){


            $template = $this->getTemplateByName($data["template"]);

            $subject = $template["subject"];
            $message = $template["message"];


        } else {

            $subject = $data["subject"] ?? "No Subject";
            $message = $data["message"] ?? "No message content.";

        }

  
        // If recipient is missing, log an error and return null
        if (!$to) {

            Log::error('EmailService: Recipient (to) is missing.', ['data' => $data]);

            return null;

        }


        try {
            
            // Create mailable object with the subject, message, and attachments
            $mailable = new Standard($subject, $message, $attachments);
           
            // Send the email
            Mail::to($to)->send($mailable);

        

        } catch (Throwable $e) {
      
            // If there is an error, log the details
            Log::alert('EmailService: Failed to send email.', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data
            ]);

            
            return null;

        }


        return true;

    }


    public function getTemplateByName($name){

        $message = "Message";

        return [
            "subject" => $name,
            "message" => $message
        ];

    }

}
