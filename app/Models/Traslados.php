<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traslados extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención plural del nombre del modelo)
    protected $table = 'traslados';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'dni',
        'num_secuencia',
        'codigo_departamento_origen',
        'nombre_departamento_origen',
        'codigo_municipio_origen',
        'nombre_municipio_origen',
        'codigo_centro_entrega_origen',
        'nombre_centro_entrega_origen',
        'codigo_departamento_destino',
        'nombre_departamento_destino',
        'codigo_municipio_destino',
        'nombre_municipio_destino',
        'codigo_centro_entrega_destino',
        'nombre_centro_entrega_destino',
        'correo',
        'telefono',
        'fecha_inicio_gestion',
        'estatus'
    ];

    // Si quieres que fecha_inicio_gestion sea tratada como instancia de Carbon:
    protected $dates = [
        'fecha_inicio_gestion',
    ];
    
}
