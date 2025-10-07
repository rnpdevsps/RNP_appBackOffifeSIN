<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'rcm_id',
        'name',
        'cargo',
        'codigo',
        'marcajeh',
        'status',
        'idDepto',
        'idMunicipio',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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
