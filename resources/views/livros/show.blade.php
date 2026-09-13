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
    <ul>
        @forelse ($livro->exemplares as $exemplar)
            <li>{{ $exemplar->codigo_patrimonio }} — {{ $exemplar->status }}</li>
        @empty
            <li>Nenhum exemplar cadastrado.</li>
        @endforelse
    </ul>
</body>
</html>