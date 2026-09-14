<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>{{ $livro->titulo }} — Webblioteca</title>
</head>

<body>
    <a href="/livros">&larr; Voltar ao catálogo</a>

    <h1>{{ $livro->titulo }}</h1>
    <p><strong>Autor:</strong> {{ $livro->autor }}</p>
    <p><strong>Categoria:</strong> {{ $livro->categoria }}</p>
    <p><strong>Ano:</strong> {{ $livro->ano_publicacao }}</p>

    <h2>Exemplares</h2>

    @if (session('sucesso'))
        <p style="color: green;">{{ session('sucesso') }}</p>
    @endif
    @if (session('erro'))
        <p style="color: red;">{{ session('erro') }}</p>
    @endif

    <ul>
        @forelse ($livro->exemplares as $exemplar)
            <li>
                {{ $exemplar->codigo_patrimonio }} — {{ $exemplar->status }}

                @auth
                    @if ($exemplar->status === 'disponivel')
                        <form action="/emprestimos" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="exemplar_id" value="{{ $exemplar->id }}">
                            <button type="submit">Emprestar</button>
                        </form>
                    @endif
                @endauth
            </li>
        @empty
            <li>Nenhum exemplar cadastrado.</li>
        @endforelse
    </ul>
</body>

</html>