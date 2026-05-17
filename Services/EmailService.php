<?php

namespace Modules\Email\Services;

use Modules\Email\Models\Email;
use Modules\Email\Models\MailTemplates;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Email\Jobs\ProcessEmail;
use Carbon\Carbon;
use Modules\Email\Mail\Standard;
use Throwable;
use Illuminate\Support\Str;
use App\Models\User;
use Modules\Cron\Services\RunCronService;
use Modules\Media\Services\MediaLibraryService;
use Modules\Email\Models\MailTemplateParam;
use Modules\Email\Services\EmailTemplateService;
use Illuminate\Http\UploadedFile;
use Modules\Email\Mail\Standard as StandardMailable;
use Modules\Email\Services\EmailDeliveryService;
use Modules\Email\Models\MailModel;
use Illuminate\Database\Eloquent\Model;


class EmailService
{


    public function send(array $data)
    {

        // Validering af obligatorisk parameter 'to'
        if (empty($data["to"])) {

            Log::error('EmailService: Recipient (to) is missing.', ['data' => $data]);

            return null;

        }


        try {

            $template = !empty($data["template"]) ? $data["template"] : null;


            if($template){
            

                $email = app(EmailTemplateService::class)->createEmailFromTemplate(
                    $data["to"], 
                    $template, 
                    $data["params"] ?? [], 
                    $data['attachments'] ?? null
                );


            } else {

                $email = $this->createEmail($data);

            }
                     

            if (!$email) {

                return null;

            }


            // Log modellen med MailModel
            if (isset($data['model']) && $data['model'] instanceof Model) {

                $this->makeModelRelation($email, $data['model']);

            }


            // Hvis send_after er i fremtiden, planlæg emailen, ellers send den med det samme
            if ($email->send_after && Carbon::now()->lt($email->send_after)) {

                $email->markAsScheduled();

                $this->createDispatch($email);

            } else {

                app(EmailDeliveryService::class)->send($email);

            }


            return $email;



        } catch (Throwable $e) {


            Log::warning('EmailService: Failed to send email.', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'data'  => $data,
            ]);


            return null;

        }


    }


  
    
    public function createEmail(array $params)
    {


        if(empty($params["subject"])){ $params["subject"] = "No subject"; } 

        if(empty($params["message"])){ $params["message"] = "No message"; } 
        


        if (empty($params["to"])) {

            Log::error("EmailService: Missing required email parameters.", ['params' => $params]);

            return null;

        }
        


        $email = Email::create([
            "template_id" => $params["template_id"] ?? null,
            "user_id"     => $params["user_id"] ?? null,
            "subject"     => $params["subject"],
            "message"     => $params["message"],
            "to"          => $params["to"],
            "status"      => $params["status"] ?? "pending",
            "params"      => $params["params"] ?? null,
            "send_after"  => $params["send_after"] ?? Carbon::now(),
        ]);

     

        if(!empty($params["attachments"])){
          
            $email->addFiles($params["attachments"],$collection = 'attachments');;     

        }
                    

        return $email;

    }



    private function makeModelRelation(Email $email, Model $model): void
    {

        MailModel::firstOrCreate(
            [
                'email_id' => $email->id,
                'model_type' => get_class($model),
                'model_id' => $model->id,
            ]
        );

    }


    public function update(Email $email, array $data)
    {


        $email->update($data);


        if ($email->send_after && Carbon::now()->lt($email->send_after)) {

            $email->markAsScheduled();

            $this->updateDispatch($email);

        } else {

            $email->refresh();

            app(EmailDeliveryService::class)->send($email);

        }

        return $email;


    }



    private function createDispatch(Email $email)
    {

        return $email->markAsScheduled();

    }


    private function updateDispatch(Email $email)
    {

        return $this->createDispatch($email);

    }

    
    public function delete(Email $email): bool
    {
        try {

            $email->delete();

            return true;

        } catch (\Throwable $e) {

            Log::error('EmailService: Failed to delete email.', [
                'error' => $e->getMessage(),
                'email_id' => $email->id ?? null,
            ]);

            return false;

        }

    }



    private function makeLabel(string $name): string
    {

        return Str::title(str_replace('.', ' ', Str::slug($name, ' ')));

    }
    
}
