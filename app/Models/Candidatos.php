<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatos extends Model
{
    use HasFactory;
    protected $table = 'candidatos';

    public $timestamps = false;

    protected $fillable = [
        'personal_id',
        'periodo',
        'foto',
        'status',
	    'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function PersonalVotaciones()
    {
        return $this->hasOne('App\Models\PersonalVotaciones', 'id', 'personal_id');
    }

    public function personal()
    {
        return $this->belongsTo(PersonalVotaciones::class, 'personal_id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
