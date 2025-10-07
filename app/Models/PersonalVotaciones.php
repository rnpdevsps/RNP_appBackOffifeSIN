<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalVotaciones extends Model
{
    use HasFactory;
    protected $table = 'personal_votaciones';

    public $timestamps = false;

    protected $fillable = [
        'dni',
        'nombre',
        'puesto',
        'ubicacion',
        'municipio',
        'periodo',
        'status',
        'flag',
	    'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
