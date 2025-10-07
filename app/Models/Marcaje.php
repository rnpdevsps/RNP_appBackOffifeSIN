<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marcaje extends Model
{
    use HasFactory;
 public $timestamps = false;

    protected $fillable = [
        'empleado_id',
        'rcm_id',
        'hora_entrada',
        'hora_salida',
	'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function Empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }

    public function Rcm()
    {
        return $this->hasOne('App\Models\Rcm', 'id', 'rcm_id');
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
