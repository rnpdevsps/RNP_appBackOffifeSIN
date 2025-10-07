<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tramite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
        'code',
        'maeplantilla_id',
        'comment',
        'file',
        'status_tramite',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function tipoTramite()
    {
        return $this->belongsTo(MaePlantilla::class, 'maeplantilla_id');
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
