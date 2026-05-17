<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;

class EmailServer extends Model
{
    protected $fillable = [
        'provider', 'host', 'port', 'username', 'password', 'encryption', 'from_name', 'from_address',
    ];
}
