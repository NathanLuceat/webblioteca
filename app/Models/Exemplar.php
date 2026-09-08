<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exemplar extends Model
{
    protected $table = 'exemplares';

    protected $fillable = ['livro_id', 'codigo_patrimonio', 'status'];

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class);
    }
}