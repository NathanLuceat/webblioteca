<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Livros — Webblioteca</title>
</head>

<body>
    <a href="/meus-emprestimos">Meus Empréstimos</a>
    <h1>Catálogo de Livros</h1>

    <ul>
        @foreach ($livros as $livro)
            <li>
                <a href="/livros/{{ $livro->id }}">
                    {{ $livro->titulo }} — {{ $livro->autor }} ({{ $livro->ano_publicacao }})
                </a>
            </li>
        @endforeach
    </ul>
</body>

</html>