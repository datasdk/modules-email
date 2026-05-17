<?php

namespace Modules\Email\Models;

use HasFactory;
use Model;

class MailTemplatesAttachments extends Model
{

    use HasFactory;
    

    protected $fillable = [
        "mail_templates_id",
        "url"
    ];


    

}
