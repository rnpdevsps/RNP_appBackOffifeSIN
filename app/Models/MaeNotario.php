<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaeNotario extends Model
{
    use HasFactory;
    protected $fillable = [
        'dni', 'name', 'country_code', 'phone', 'Status', 'created_by'
    ];
}
