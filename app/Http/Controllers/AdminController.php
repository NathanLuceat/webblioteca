<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Sala;
use App\Models\ReservaSala;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function painel()
    {
        return view('admin.painel');
    }

    public function livrosForm()
    {
        return view('admin.livros');
    }

    public function livrosStore(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'ano_publicacao' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'qtd_exemplares' => 'required|integer|min:1|max:50',
        ]);

        $livro = Livro::create([
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'categoria' => $request->categoria,
            'ano_publicacao' => $request->ano_publicacao,
            'temporario' => true,
        ]);

        for ($i = 1; $i <= $request->qtd_exemplares; $i++) {
            $livro->exemplares()->create([
                'codigo_patrimonio' => 'TMP-' . $livro->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'status' => 'disponivel',
            ]);
        }

        return redirect()->route('admin.painel')
            ->with('sucesso', "Livro \"{$livro->titulo}\" cadastrado com {$request->qtd_exemplares} exemplar(es).");
    }

    public function salasForm()
    {
        return view('admin.salas');
    }

    public function salasStore(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'capacidade' => 'required|integer|min:1|max:500',
            'localizacao' => 'required|string|max:255',
        ]);

        $sala = Sala::create([
            'nome' => $request->nome,
            'capacidade' => $request->capacidade,
            'localizacao' => $request->localizacao,
            'temporario' => true,
        ]);

        return redirect()->route('admin.painel')
            ->with('sucesso', "Sala \"{$sala->nome}\" cadastrada com sucesso.");
    }

    public function reservas()
    {
        $reservas = ReservaSala::with(['sala', 'usuario'])
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get();

        return view('admin.reservas', ['reservas' => $reservas]);
    }
}
