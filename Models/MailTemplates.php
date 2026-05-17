<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Mail\Mailable;

use DataSDK\Tools\Traits\Language;
use Spatie\Translatable\HasTranslations;
use DataSDK\Categories\Traits\Categories;
use DataSDK\Tools\Traits\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Email\Models\MailTemplateParam;
use DataSDK\Tools\Traits\Slugs\Slugs;
use Illuminate\Database\Eloquent\Model;

class MailTemplates extends Model
{

    use Language;
    use Categories, DateFormat;
    use HasFactory;
    use Slugs;
    

    protected $fillable = [
        "name",
        "subject",
        "mailable",
        "contract",
        "html_template",
        "text_template",
        "params",
        "active"
    ];

    public $sluggable = 'subject';

    public $slugStorageAttribute = 'name';


    protected $casts = [
        'params' => 'array',
        'active' => 'boolean'
    ];

    protected $table = "mail_templates";

    protected $translatable = ['subject', 'html_template', 'text_template'];


    public function params()
    {
        return $this->hasMany(MailTemplateParam::class, 'template_id');
    }


}
