<?php

namespace Modules\Email\Models;

use Modules\Email\Models\Email;
use Illuminate\Support\Facades\Log;
use Modules\Email\Jobs\ProcessEmail;
use Modules\Email\Models\MailTemplates;
use Illuminate\Mail\Mailable;
use App\Mail\Standard;
use Illuminate\Support\Facades\Storage;
use SendGrid as SG;
use SendGrid\Mail\Mail;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\To;
use SendGrid\Mail\From;
use SendGrid\Mail\Content;
use SendGrid\Mail\ReplyTo;
use SendGrid\Mail\Subject;
use SendGrid\Mail\SendAt;
use SendGrid\Mail\Attachment;
use Exception;
use Modules\Email\Services\EmailService;


class SendGrid {

  

    public function __invoke(){

        $data = $this->email->toArray();
        $default = [
            "from" => config('mail.from.address'),
            "reply_address" => config('mail.from.reply_address'),
            "subject" => "no subject",
            "message" => "no message",
            "error" => null,
            "template_id" => null,
            "attachments" => []
        ];      

        $data = array_merge($default,$data);

        $apiKey = config("mail.mailers.sendgrid.api");
        if(!$apiKey){
            Log::warning("No api-key for sendgrid is defined");
        }
        
        $sg = new SG(trim($apiKey));
        $mail = new Mail();
        $personalization = new Personalization();
        $personalization->addTo(new To($data["to"]));
        $mail->addPersonalization($personalization);
        $mail->setSubject(new Subject($data["subject"]));
        
        $mail->addContent(new Content("text/html", $data["message"]));
  
        $mail->setFrom(new From(config("mail.from.address"),config("mail.from.name")));
        $mail->setReplyTo(new ReplyTo(config("mail.from.reply_address")));

        if(isset($data["send_after"])){
            $sendAfter = is_string($data["send_after"]) ? strtotime($data["send_after"]) : $data["send_after"];
            $mail->setSendAt(new SendAt($sendAfter));
        }
        
        if(isset($data["attachments"]) && is_array($data["attachments"])){
            foreach($data["attachments"] as $attachment){
                if(empty($attachment)){ continue; }
                $path = str_replace("\\","/", Storage::disk("local")->path($attachment));
                if (file_exists($path) && is_file($path)) {
                    if(!is_readable($path)){
                        Log::warning("Attachments is not readable: $path");
                    }
                    if(!chmod($path, 0755)){
                        Log::warning("Attachments permission denied: $path");
                    }
                    $fileEncoded = base64_encode(file_get_contents($path));
                    clearstatcache();
                    $filename = ucfirst(str_replace("_"," ",basename($path)));
                    $attachment = new Attachment();
                    $attachment->setContent($fileEncoded);
                    $attachment->setFilename($filename);
                    $attachment->setType("application/pdf");
                    $attachment->setDisposition("attachment");
                    $mail->addAttachment($attachment);
                } else {
                    Log::warning("Attachments does not exist: $path");
                }
            }
        }
        
        $requestBody = $mail;

        try {
            $response = $sg->client->mail()->send()->post($requestBody);
        } catch (Throwable $ex) {
            $ex->getMessage();
        }
        
        $statusCode = $response->statusCode();
        if($isSuccessful = self::isSuccessful($statusCode)){
            return true;
        } else {
            $body = json_decode($response->body(),true);
            $errors = json_encode($body["errors"]);
            throw new Exception($errors); 
            return false;
        }

    }

    public static function isSuccessful($statusCode){
        return $statusCode >= 200 && $statusCode <= 260;
    }

    public static function jobSendMails($data){
        $default = [
            "from" => config('mail.from.address'),
            "reply_address" => config('mail.from.reply_address'),
            "subject" => "No subject",
            "message" => "No message",
            "error" => null,
            "attachments" => []
        ];      
          
        $data = array_merge($default,$data);
        
        return  app(EmailService::class)->send($data);    
        

    }

}
