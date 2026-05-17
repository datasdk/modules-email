<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\Email\Models\MailTemplates;
use DataSDK\Tools\Traits\Language;
use DataSDK\Tools\Traits\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Media\Traits\InteractsWithMedia;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Modules\Media\Contracts\HasMedia;
use ActionModel;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Email\Services\EmailService;
use Modules\Cron\Contracts\CronJobInterface;
use Modules\Cron\Traits\HasCronJob;
use Modules\Email\Services\EmailDeliveryService;
use Modules\Media\Models\Media;

class Email extends Media implements HasMedia, CronJobInterface
{
 
    use HasFactory;
    use InteractsWithMedia;
   // use SoftDeletes;
    use HasCronJob;
    use DateFormat;

 
    protected $table = "emails";

    protected $translatable = []; // extendtion has trans... make it neutral here..

    protected $appends = [
        "attachments"
    ];

    protected $fillable = [
        "to",
        "user_id",
        "subject",
        "message",
        "template_id",
        "status",
        "send_after",
        "params",
        "sent",
        "errors"
    ];

    protected $casts = [
        "params" => "array",
        "errors" => "array",
        "sent" => "datetime",
        "send_after" => "datetime"
    ];

    protected $hidden = [
        "media"
    ];




  
    public function getAttachmentsAttribute(){

        return $this->getMediaParametersFromMediaCollection(
            $this->getMedia('attachments')
        );

    }


    public function template(){

        return $this->hasOne(MailTemplates::class, 'id', 'template_id');
    }


    public function send(){
    
   
        return app(EmailService::class)->send($this->toArray());

    }


    public function hasErrors(){

        return $this->errors !== null;

        
    }

    public function addError(string|array $error): void
    {
        // Hent eksisterende errors
        $currentErrors = $this->errors ? $this->errors: [];

        // Konverter string til array, hvis nødvendigt
        if (is_string($error)) {
            $error = [$error];
        }

        // Tilføj nye errors
        $currentErrors = array_merge($currentErrors, $error);

        // Gem opdateret errors
        $this->update([
            'errors' => $currentErrors,
        ]);
    }


    public function resetErrors(){

        if(!is_null($this->errors)){

            $this->update([
                'errors' => null,
            ]);

        }
       
        return $this;
        
    }
    

    public function hasTemplateMarkers(){
  
        $hasTemplateMarkers = false;

        if (strpos($this->subject, '{{') !== false || strpos($this->subject, '}}') !== false) {
            $hasTemplateMarkers = true;
        }

        if (strpos($this->message, '{{') !== false || strpos($this->message, '}}') !== false) {
            $hasTemplateMarkers = true;
        }

        return $hasTemplateMarkers;

    }


    public function canSend(): bool
    {
        if ($this->template && $this->template->contract) {
            $contractClass = $this->template->contract;

            if (class_exists($contractClass)) {

                $contract = new $contractClass();
    
                return (bool) $contract->canSend($this);
              
            }
        }

        return true;
    }



}
