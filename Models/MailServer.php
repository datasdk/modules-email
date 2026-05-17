<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Settings;


class MailServer extends Model
{

    use HasFactory;

    
    protected $fillable = [
        'provider',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'sender_name',
        'sender_email',
        'reply_to_email',
        'is_default',
    ];

    /**
     * Get the default email server.
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }


    public static function getMailConfig(){

        $smtpConfig = config("mail");

        $settings = config("mail") ?? [];

        
        return array_merge($smtpConfig, $settings);

    }

}
