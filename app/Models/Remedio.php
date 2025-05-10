<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remedio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'horario',
        'dosagem',
        'frequencia',
        'imagem',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
