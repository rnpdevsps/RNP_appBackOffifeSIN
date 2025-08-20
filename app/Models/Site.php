<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $fillable = ['account_id', 'site_name', 'property_id', 'property_name', 'accessToken', 'refreshToken', 'created_by'];
}
