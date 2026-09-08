<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $table = 'salas';

    protected $fillable = ['nome', 'capacidade', 'localizacao'];

    public function reservas()
    {
        return $this->hasMany(ReservaSala::class);
    }
}