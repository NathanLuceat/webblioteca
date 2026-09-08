<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    protected $table = 'emprestimos';

    protected $fillable = [
        'usuario_id', 'exemplar_id', 'data_emprestimo',
        'data_prevista_devolucao', 'data_devolucao',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function exemplar()
    {
        return $this->belongsTo(Exemplar::class);
    }
}