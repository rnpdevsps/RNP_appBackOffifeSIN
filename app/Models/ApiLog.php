<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'ip_address',
        'endpoint',
        'service_name',
        'user_agent',
        'api_key',
        'request_data',
        'message',
        'status',
        'url',
        'method', 
        'headers', 
        'request_body',
        'status_code', 
        'response_body', 
        'execution_time',
        'http_status',
        'execution_time_ms',
        'uuid'
    ];
    
    protected $casts = [
        'headers' => 'array',
        'request_body' => 'array',
        'response_body' => 'array',
    ];
}
