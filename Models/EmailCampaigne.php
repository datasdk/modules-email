<?php

namespace Modules\Email\Models;

use HasFactory;
use ActionModel;
use Modules\Email\Models\MailTemplates;
use DataSDK\Tools\Traits\Language;


use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Category;
use User;
use Modules\Email\Services\EmailService;


class EmailCampaigne extends ActionModel
{

  
  

    public $translatable = ['slug'];

    public $sluggable = 'id';

    protected $table = "mail_campaigns";


    protected $fillable = [
        "template_id",
        "send_day",
        "send_time",
    ];


    public function getSluggable()
    {
        return 'campaigne';
    }


    public function template()
    {
        return $this->belongsTo(MailTemplates::class);
    }


    public function isSentToUser($user_id)
    {
        return $this->hasOne(Email::class,"email_id")->where("user_id",$user_id)->exists();
    }


    public static function send($user_id, $category_id = [], $from_date = null, $first_is_today = true, $with_childen = true, $avoid_duplicate = false)
    {

        $result = [];

        if ($with_childen) {
            $categoires = Category::getAllChildren($category_id);
        } else {
            $categoires = $category_id;
        }

        if (empty($categoires)) {
            return null;
        }


        $campanige = EmailCampaigne::category($categoires)->get();

        if (empty($campanige)) {
            Log::notice("E-mail campaigne is empty");
            return null;
        }

        if (!$from_date) {
            $from_date = date("c");
        }


        foreach ($campanige as $number => $item) {
            $campanige_id = $item->id;
            $template = $item->template;
            $template_id = $template->id;
            $send_day  = $item->send_day ;
            $send_time = $item->send_time;
            $number++;

            if ($first_is_today) {
                $send_day -= 1;
            }

            $user = User::find($user_id);
            $send_after = strtotime("midnight +".($send_day)." days", strtotime($from_date));

            $params = [
                "to" => $user->email,
                "user_id" => $user->id,
                "template_id" => $template_id,
                "send_after" => date("c",$send_after),
                "send_at_date" => date("c",$send_after),
                "avoid_duplicate" => $avoid_duplicate,
                "params"=>[
                    "send_day" => $send_day,
                    "send_time" => $send_time,
                    "from_date" => $from_date,
                    "number" => $number
                ] + $user->toArray()
            ];
            
            


            if ($is_sent = app(EmailService::class)->send($params)) {
                $params["status"] = "send";
                $result[]= $params;
            } else {
                $params["status"] = "not send";
            }

            activity('email')
                ->causedBy($user)
                ->withProperties($params)
                ->log("send campaign ".$campanige_id);

        }


        return $result;

    }
}
