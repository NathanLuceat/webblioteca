<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\ReservaSala;

class SalaController extends Controller
{
    public function index()
    {
        $salas = Sala::all();

        return view('salas.index', ['salas' => $salas]);
    }

    public function show($id)
    {
        $sala = Sala::findOrFail($id);
        $dias = ReservaSala::diasPermitidos();
        $blocos = array_keys(ReservaSala::blocosDisponiveis());

        $reservasExistentes = ReservaSala::where('sala_id', $id)
            ->whereIn('data', array_keys($dias))
            ->get();

        $ocupados = [];
        foreach ($reservasExistentes as $r) {
            $chave = substr($r->hora_inicio, 0, 5) . '-' . substr($r->hora_fim, 0, 5);
            $ocupados[$r->data][] = $chave;
        }

        return view('salas.show', [
            'sala' => $sala,
            'dias' => $dias,
            'blocos' => $blocos,
            'ocupados' => $ocupados,
        ]);
    }
}