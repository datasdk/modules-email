<?php

namespace Modules\Email\Models;

use HasFactory;
use Model;


class EmailPlaceholders extends Model
{

    protected $table = "mail_template_placeholders";

    protected $fillable = [
        "mail_template_id",
        "name",
        "description",
        "meta"
    ];

   
        
}
