<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraNotario extends Model
{
    use HasFactory;

    public $fillable = [
        'notario_id',
        'estadoinhabilitado_id',
        'observaciones',
        'adjunto',
        'created_by'
    ];
}
