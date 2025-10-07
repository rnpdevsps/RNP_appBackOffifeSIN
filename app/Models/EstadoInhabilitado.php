<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoInhabilitado extends Model
{
    use HasFactory;
    protected $table = 'estado_inhabilitados';
    protected $fillable = [
        'id','descripcion','created_at','updated_at','created_by','updated_by','deleted_at','deleted_by','status'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
