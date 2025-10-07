<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rcm extends Model
{
    use HasFactory;

    protected $table = 'rcms';

    protected $fillable = [
        'codigo',
        'name',
        'idDepto',
        'idMunicipio',
        'status',
        'id_clasificacion',
        'direccion',
        'foto',
        'latitud',
        'longitud',
        'telefono',
        'date_end_inactive',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function Clasificacion()
    {
        return $this->hasOne('App\Models\Clasificacion', 'id', 'id_clasificacion')->orderBy('created_at', 'desc');
    }

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
