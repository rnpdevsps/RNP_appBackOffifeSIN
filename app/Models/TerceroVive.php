<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerceroVive extends Model
{
    use HasFactory;
    protected $table = 'tercero_con_quien_vive';
    
    protected $fillable = [
        'id_prenna',
        'dni',
        'nombre',
        'correo',
        'telefono',
        'domicilio_completo',
        'departamento_code',
        'departamento_label',
        'municipio_code',
        'municipio_label',
        'city_code',
        'city_label',
        'barrio_code',
        'barrio_label',
        'direccion_exacta'
    ];
}
