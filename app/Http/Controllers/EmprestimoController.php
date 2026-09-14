<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Exemplar;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    public function index()
    {
        $emprestimos = Emprestimo::where('usuario_id', auth()->id())
            ->whereNull('data_devolucao')
            ->with('exemplar.livro')
            ->get();

        return view('emprestimos.index', ['emprestimos' => $emprestimos]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exemplar_id' => 'required|exists:exemplares,id',
        ]);

        $exemplar = Exemplar::findOrFail($request->exemplar_id);

        if ($exemplar->status !== 'disponivel') {
            return back()->with('erro', 'Esse exemplar não está disponível no momento.');
        }

        Emprestimo::create([
            'usuario_id' => auth()->id(),
            'exemplar_id' => $exemplar->id,
            'data_emprestimo' => now(),
            'data_prevista_devolucao' => now()->addDays(7),
        ]);

        $exemplar->update(['status' => 'emprestado']);

        return back()->with('sucesso', 'Livro emprestado com sucesso! Devolução em 7 dias.');
    }

    public function devolver(Emprestimo $emprestimo)
    {
        if ($emprestimo->usuario_id !== auth()->id()) {
            abort(403);
        }

        $emprestimo->update(['data_devolucao' => now()]);
        $emprestimo->exemplar->update(['status' => 'disponivel']);

        return redirect('/meus-emprestimos')->with('sucesso', 'Livro devolvido com sucesso!');
    }
}