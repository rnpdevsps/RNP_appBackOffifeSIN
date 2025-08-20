<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'ip_address',
        'endpoint',
        'user_agent',
        'api_key',
        'request_data',
        'message',
        'status',
    ];
}
