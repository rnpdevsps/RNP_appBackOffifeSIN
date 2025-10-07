<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcajesLog extends Model
{
    use HasFactory;

      // Nombre exacto de la tabla (respetando el pedido)
    protected $table = 'marcajesLog';

    protected $fillable = [
        'codigo',
        'mensaje',
        'endpoint',
        'resultado',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
