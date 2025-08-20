<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimerSessionBreak extends Model
{
    use HasFactory;

    protected $fillable = ['timer_session_id','break_start_time','break_end_time'];
}
