<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    protected $table = 'livros';

    protected $fillable = ['titulo', 'autor', 'categoria', 'ano_publicacao'];

    public function exemplares()
    {
        return $this->hasMany(Exemplar::class);
    }
}