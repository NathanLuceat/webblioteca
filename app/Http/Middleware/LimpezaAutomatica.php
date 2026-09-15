<?php

namespace App\Http\Middleware;

use App\Models\ReservaSala;
use App\Models\Livro;
use App\Models\Sala;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LimpezaAutomatica
{
    public function handle(Request $request, Closure $next): Response
    {
        // Remove reservas expiradas de todos os usuários
        $agora = Carbon::now('America/Sao_Paulo');
        $expiradas = ReservaSala::all()->filter(function ($reserva) use ($agora) {
            return Carbon::parse($reserva->data . ' ' . $reserva->hora_fim)->isPast();
        });

        foreach ($expiradas as $reserva) {
            $reserva->delete();
        }

        // Remove livros temporários com mais de 24 horas
        Livro::where('temporario', true)
            ->where('created_at', '<=', $agora->copy()->subHours(24))
            ->delete();

        // Remove salas temporárias com mais de 24 horas
        Sala::where('temporario', true)
            ->where('created_at', '<=', $agora->copy()->subHours(24))
            ->delete();

        return $next($request);
    }
}
