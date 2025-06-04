<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrequenciaCardiaca extends Model
{
    use HasFactory;

    protected $table = 'frequencia_cardiaca';
    protected $fillable = [
        'user_id',
        'frequencia',
        'data',
        'horario',
        'observacao'
    ];

    protected $casts = [
        'data' => 'date',
        'horario' => 'datetime:H:i'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
