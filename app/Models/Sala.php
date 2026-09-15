<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $table = 'salas';

    protected $fillable = ['nome', 'capacidade', 'localizacao', 'temporario'];

    protected function casts(): array
    {
        return ['temporario' => 'boolean'];
    }

    public function reservas()
    {
        return $this->hasMany(ReservaSala::class);
    }
}