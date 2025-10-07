<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleContrato extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'detalle_contratos';

    protected $fillable = [
        'contrato_id',
        'anio',
        'fecha_inicio',
        'fecha_final',
        'valor_metros2',
        'valor_mensual',
        'no_meses',
        'valor_total',
        'costo_dicional',
        'observaciones_det',
        'status',
        'moneda',
        'adjunto',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function User()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }



}
