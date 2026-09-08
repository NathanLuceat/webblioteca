<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaSala extends Model
{
    protected $table = 'reservas_salas';

    protected $fillable = ['usuario_id', 'sala_id', 'data', 'hora_inicio', 'hora_fim'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
}