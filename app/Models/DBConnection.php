<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DBConnection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'host',
        'port',
        'database',
        'is_active',
    ];
}
