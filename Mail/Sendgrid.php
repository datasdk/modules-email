<?php

namespace Modules\Email\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Modules\Email\Models\Email;
use SendGrid\Mail\Mail as SendGridMail;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\To;
use SendGrid\Mail\From;
use SendGrid\Mail\Content;
use SendGrid\Mail\ReplyTo;
use SendGrid\Mail\Subject;
use SendGrid\Mail\SendAt;
use SendGrid\Mail\Attachment;
use Exception;

class Sendgrid extends Mailable
{
    
    use Queueable, SerializesModels;

    
    public ?string $logName = "email";
    public bool $submitEmptyLogs = true;
    public $data = [];
    public $email;


    public function __construct(Email $email)
    {
        $data = $email->toArray();
        $this->data = array_merge($this->default(), $data);
        $this->email = (new Email)->storeEmailInDatabase($this->data);
    }



    public function build()
    {

        $data = $this->data;


        $apiKey = config("services.sendgrid.api_key");

        if (!$apiKey) {
            Log::warning("No api-key for sendgrid is defined");
            return false;
        }


        $sg = new \SendGrid($apiKey);
        $mail = new SendGridMail();

        $personalization = new Personalization();


        $personalization->addTo(new To($data["to"]));
        $mail->addPersonalization($personalization);
        $mail->setSubject(new Subject($data["subject"]));
        $mail->addContent(new Content("text/html", $data["message"]));
        $mail->setFrom(new From(config("mail.from.address"), config("mail.from.name")));
        $mail->setReplyTo(new ReplyTo(config("mail.from.reply_address")));


        if (isset($data["send_after"])) {

            $sendAfter = is_string($data["send_after"]) ? strtotime($data["send_after"]) : $data["send_after"];

            $mail->setSendAt(new SendAt($sendAfter));

        }


        if (isset($data["attachments"]) && is_array($data["attachments"])) {


            foreach ($data["attachments"] as $attachment) {


                if (empty($attachment)) { continue; }

                $path = str_replace("\\", "/", Storage::disk("local")->path($attachment));


                if (file_exists($path) && is_file($path)) {

                    if (!is_readable($path)) {
                        Log::warning("Attachments is not readable: $path");
                    }

                    if (!chmod($path, 0755)) {
                        Log::warning("Attachments permission denied: $path");
                    }


                    $fileEncoded = base64_encode(file_get_contents($path));
                    clearstatcache();
                    $filename = ucfirst(str_replace("_", " ", basename($path)));
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


        try {


            $response = $sg->client->mail()->send()->post($mail);


        } catch (Throwable $ex) {

            Log::error("SendGrid error: " . $ex->getMessage());

            return false;

        }


        $statusCode = $response->statusCode();


        if (self::isSuccessful($statusCode)) {

            return true;

        } else {


            $body = json_decode($response->body(), true);
            $errors = json_encode($body["errors"]);
            throw new Exception($errors);

            return false;

        }
    }

    private function default()
    {
        return [
            "title" => "",
            "message" => "",
            "attachments" => [],
            "template" => null,
            "view" => config("mail.view", "emails.standard"),
            "params" => [],
            "from" => config('mail.from.address'),
            "reply_address" => config('mail.from.reply_address'),
            "error" => null,
            "send_after" => null,
            "cc" => null,
            "bcc" => null,
        ];
    }

    public static function isSuccessful($statusCode)
    {
        return $statusCode >= 200 && $statusCode <= 299;
    }
    
}