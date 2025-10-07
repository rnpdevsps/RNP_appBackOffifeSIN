<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosContratos extends Model
{
    use HasFactory;
    protected $table = 'estados_contratos';
    protected $fillable = [
        'id','descri','created_at','updated_at','created_by','updated_by','deleted_at','deleted_by','status'
    ];
}
