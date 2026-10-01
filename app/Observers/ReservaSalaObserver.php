<?php

namespace App\Observers;

use App\Models\ReservaSala;
use App\Models\Activity;
use Carbon\Carbon;

class ReservaSalaObserver
{
    public function created(ReservaSala $reserva): void
    {
        Activity::create([
            'type' => 'reserva.criada',
            'entity_type' => ReservaSala::class,
            'entity_id' => $reserva->id,
            'actor_id' => auth()->id(),
            'summary' => [
                'sala' => $reserva->sala->nome,
                'data' => $reserva->data,
                'hora_inicio' => $reserva->hora_inicio,
                'hora_fim' => $reserva->hora_fim,
            ],
        ]);
    }

    public function deleted(ReservaSala $reserva): void
    {
        $expirada = Carbon::parse($reserva->data . ' ' . $reserva->hora_fim)->isPast();

        Activity::create([
            'type' => $expirada ? 'reserva.expirada' : 'reserva.cancelada',
            'entity_type' => ReservaSala::class,
            'entity_id' => $reserva->id,
            'actor_id' => $expirada ? null : auth()->id(),
            'summary' => [
                'sala' => $reserva->sala->nome,
                'data' => $reserva->data,
                'hora_inicio' => $reserva->hora_inicio,
                'hora_fim' => $reserva->hora_fim,
            ],
        ]);
    }
}