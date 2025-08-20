<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = [
        'api_key',
        'app_name',
        'status',
        'permissions',
        'expires_at',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];
    

    // Desactiva las claves incrementales si no las necesitas
    //public $incrementing = true;
}
