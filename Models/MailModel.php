<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MailModel extends Model
{
    protected $table = 'mail_models';

    protected $fillable = [
        'email_id',
        'model_type',
        'model_id'
    ];

    /**
     * Polymorf relation til den model, som emailen er sendt til
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relation til Email modellen
     */
    public function email()
    {
        return $this->belongsTo(Email::class, 'email_id');
    }
}
