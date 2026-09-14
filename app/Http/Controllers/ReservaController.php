<?php

namespace App\Http\Controllers;

use App\Models\ReservaSala;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function index()
    {
        $expiradas = ReservaSala::where('usuario_id', auth()->id())->get()
            ->filter(fn ($r) => Carbon::parse($r->data . ' ' . $r->hora_fim)->isPast());

        foreach ($expiradas as $reserva) {
            $reserva->delete();
        }

        $reservas = ReservaSala::where('usuario_id', auth()->id())
            ->with('sala')
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get();

        return view('reservas.index', ['reservas' => $reservas]);
    }

    public function store(Request $request)
    {
        $diasPermitidos = array_keys(ReservaSala::diasPermitidos());
        $blocosPermitidos = ReservaSala::blocosDisponiveis();

        $request->validate([
            'sala_id' => 'required|exists:salas,id',
            'dias' => 'required|array|min:1',
            'dias.*' => [Rule::in($diasPermitidos)],
            'blocos' => 'required|array|min:1|max:3',
            'blocos.*' => [Rule::in(array_keys($blocosPermitidos))],
        ]);

        $usuarioId = auth()->id();
        $salaId = $request->sala_id;
        $diasSelecionados = $request->dias;
        $blocosSelecionados = $request->blocos;

        foreach ($diasSelecionados as $data) {
            $jaReservados = ReservaSala::where('usuario_id', $usuarioId)
                ->where('sala_id', $salaId)
                ->where('data', $data)
                ->count();

            if ($jaReservados + count($blocosSelecionados) > 3) {
                return back()->with('erro', "Limite de 3 blocos por dia excedido no dia {$data}.");
            }

            foreach ($blocosSelecionados as $blocoKey) {
                [$inicio, $fim] = $blocosPermitidos[$blocoKey];

                $conflitoSala = ReservaSala::where('sala_id', $salaId)
                    ->where('data', $data)
                    ->where('hora_inicio', $inicio)
                    ->exists();

                if ($conflitoSala) {
                    return back()->with('erro', "O horário {$blocoKey} do dia {$data} já está reservado nesta sala.");
                }

                $conflitoUsuario = ReservaSala::where('usuario_id', $usuarioId)
                    ->where('data', $data)
                    ->where('hora_inicio', $inicio)
                    ->exists();

                if ($conflitoUsuario) {
                    return back()->with('erro', "Você já tem outra reserva no horário {$blocoKey} do dia {$data}.");
                }
            }
        }

        foreach ($diasSelecionados as $data) {
            foreach ($blocosSelecionados as $blocoKey) {
                [$inicio, $fim] = $blocosPermitidos[$blocoKey];

                ReservaSala::create([
                    'usuario_id' => $usuarioId,
                    'sala_id' => $salaId,
                    'data' => $data,
                    'hora_inicio' => $inicio,
                    'hora_fim' => $fim,
                ]);
            }
        }

        return back()->with('sucesso', 'Sala reservada com sucesso!');
    }

    public function cancelar(ReservaSala $reserva)
    {
        if ($reserva->usuario_id !== auth()->id()) {
            abort(403);
        }

        $reserva->delete();

        return redirect('/minhas-reservas')->with('sucesso', 'Reserva cancelada.');
    }
}