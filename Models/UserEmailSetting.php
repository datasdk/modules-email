<?php
// app/Models/UserEmailSetting.php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmailSetting extends Model
{
    use HasFactory;

    // Define table name if different from default
    protected $table = 'user_email_settings';

    // Fillable attributes
    protected $fillable = [
        'user_id',
        'template_id',
        'active'
    ];

    /**
     * Scope to check if the email template is active for a given user.
     *
     * @param $query
     * @param $userId
     * @param $templateName
     * @return mixed
     */
    public static function userAlowsEmailTemplate($userId, $template_id)
    {   
        
        $query = self::where('user_id', $userId)
            ->where('template_id', $template_id);
            
        if(!$query->exists()){ 
            
            return true; 
        
        } 

        if($query->where('active', 1)->exists()){
            
            return true;

        }

        return false;
        
    }
}
