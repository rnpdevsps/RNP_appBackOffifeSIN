<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeCategory extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'created_by'
    ];
}
