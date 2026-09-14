<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Salas — Webblioteca</title>
</head>
<body>
    <h1>Salas Disponíveis</h1>
    <ul>
        @foreach ($salas as $sala)
            <li>
                <a href="/salas/{{ $sala->id }}">
                    {{ $sala->nome }} — capacidade: {{ $sala->capacidade }} ({{ $sala->localizacao }})
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>