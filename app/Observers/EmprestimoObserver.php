<?php

namespace App\Observers;

use App\Models\Emprestimo;
use App\Models\Activity;

class EmprestimoObserver
{
    public function created(Emprestimo $emprestimo): void
    {
        Activity::create([
            'type' => 'emprestimo.criado',
            'entity_type' => Emprestimo::class,
            'entity_id' => $emprestimo->id,
            'actor_id' => auth()->id(),
            'summary' => [
                'livro' => $emprestimo->exemplar->livro->titulo,
                'exemplar' => $emprestimo->exemplar->codigo_patrimonio,
                'data_prevista_devolucao' => $emprestimo->data_prevista_devolucao,
            ],
        ]);
    }

    public function updated(Emprestimo $emprestimo): void
    {
        if ($emprestimo->wasChanged('data_devolucao') && $emprestimo->data_devolucao !== null) {
            Activity::create([
                'type' => 'emprestimo.devolvido',
                'entity_type' => Emprestimo::class,
                'entity_id' => $emprestimo->id,
                'actor_id' => auth()->id(),
                'summary' => [
                    'livro' => $emprestimo->exemplar->livro->titulo,
                    'exemplar' => $emprestimo->exemplar->codigo_patrimonio,
                ],
            ]);
        }
    }
}