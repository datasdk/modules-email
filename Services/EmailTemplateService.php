<?php

namespace Modules\Email\Services;

use Modules\Email\Models\Email;
use Modules\Email\Models\MailTemplates;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Email\Jobs\ProcessEmail;
use Carbon\Carbon;

use Throwable;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Arr;
use Modules\Media\Services\MediaLibraryService;
use Modules\Email\Models\MailTemplateParam;
use Modules\Email\Services\EmailService;
use Illuminate\Support\Facades\Artisan;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Shortcode;




class EmailTemplateService
{




    public function createEmailFromTemplate(string $to, string $templateName, array $params = [], null|array|MediaCollection $attachments = null,$send_at = null)
    {

        $template = $this->getTemplate($templateName);
        
    
        if (!$template) {

            Log::warning("EmailService: Template not found.", ['template' => $templateName]);

            return null;

        }



        $user = User::where('email', $to)->first();

        $subject = $template->subject;
        $message = $template->html_template;


        // add default param
        $params["to_email"] = $to;


        $subject = $this->replaceTagsWithParams($subject,$params);

        $message = $this->replaceTagsWithParams($message,$params);
       

        $subject = Shortcode::compile($subject);

        $message = Shortcode::compile($message);



        $emailParams = [
            "to"          => $to,
            "template_id" => $template->id,
            "user_id"     => $user->id ?? null,
            "subject"     => $subject,
            "message"     => $message, 
            "status"      => "pending",
            "params"      => $params ?? null,
            "send_after"  => $send_at ?? Carbon::now(),
            "attachments" => $attachments,
        ];


        return app(EmailService::class)->createEmail($emailParams);

    }

    

    private function replaceTagsWithParams(string $string, array $params){


        $defaultParams = [
            "app.name" => config("app.name")
        ];


        $params = array_merge($params,$defaultParams);
        
        $dotArray = collect($params)->dot();


        foreach($dotArray as $name => $value){

            $string = str_replace([
                "{{".$name."}}",
                "{{ ".$name." }}",
                "{{".$name." }}",
                "{{ ".$name."}}"
            ],$value,$string);
            
        }


        return preg_replace('/{{\s*[^}]+\s*}}/', '', $string);

    }

  
    private function getTemplate(string $templateName){
        
        if(!$templateName){ return null; }

        return MailTemplates::where("name", $templateName)->first();

    }

    
    private function convertToArray($value)
    {
        if ($value instanceof Collection) {
            $value = $value->toArray();
        } elseif (is_object($value)) {
            if (method_exists($value, 'toArray')) {
                $value = $value->toArray();
            } else {
                // Cast til array via get_object_vars
                $value = get_object_vars($value);
            }
        }

        if (is_array($value)) {
            foreach ($value as $key => $v) {
                $value[$key] = $this->convertToArray($v);
            }
        }

        return $value;
    }
    
}
