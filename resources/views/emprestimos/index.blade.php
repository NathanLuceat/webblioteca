<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Empréstimos — Webblioteca</title>
</head>
<body>
    <a href="/livros">&larr; Catálogo</a>
    <h1>Meus Empréstimos</h1>

    @if (session('sucesso'))
        <p style="color: green;">{{ session('sucesso') }}</p>
    @endif

    <ul>
        @forelse ($emprestimos as $emprestimo)
            <li>
                {{ $emprestimo->exemplar->livro->titulo }}
                ({{ $emprestimo->exemplar->codigo_patrimonio }})
                — devolução prevista: {{ $emprestimo->data_prevista_devolucao }}

                <form action="/emprestimos/{{ $emprestimo->id }}/devolver" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Devolver</button>
                </form>
            </li>
        @empty
            <li>Você não tem nenhum empréstimo ativo.</li>
        @endforelse
    </ul>
</body>
</html>