<?php

namespace App\Observers;

use App\Models\Livro;
use App\Models\Activity;

class LivroObserver
{
    public function created(Livro $livro): void
    {
        Activity::create([
            'type' => 'livro.criado',
            'entity_type' => Livro::class,
            'entity_id' => $livro->id,
            'actor_id' => auth()->id(),
            'summary' => [
                'titulo' => $livro->titulo,
                'autor' => $livro->autor,
            ],
        ]);
    }

    public function deleted(Livro $livro): void
    {
        Activity::create([
            'type' => 'livro.expirado',
            'entity_type' => Livro::class,
            'entity_id' => $livro->id,
            'actor_id' => null,
            'summary' => [
                'titulo' => $livro->titulo,
            ],
        ]);
    }
}