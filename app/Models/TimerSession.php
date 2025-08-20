<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimerSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'form_id', 'form_value_id', 'start_time', 'stop_time', 'status'];
}
