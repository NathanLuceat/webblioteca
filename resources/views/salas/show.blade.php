<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{ $sala->nome }} — Webblioteca</title>
</head>
<body>
    <a href="/salas">&larr; Voltar</a>
    <h1>{{ $sala->nome }}</h1>
    <p>Capacidade: {{ $sala->capacidade }}</p>
    <p>Localização: {{ $sala->localizacao }}</p>

    @if (session('sucesso'))
        <p style="color: green; font-weight: bold;">{{ session('sucesso') }}</p>
    @endif
    @if (session('erro'))
        <p style="color: red; font-weight: bold;">{{ session('erro') }}</p>
    @endif
    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $mensagem)
                <li>{{ $mensagem }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Horários já ocupados</h2>
    @foreach ($dias as $valor => $rotulo)
        <p>
            <strong>{{ $rotulo }}:</strong>
            @if (!empty($ocupados[$valor]))
                {{ implode(', ', $ocupados[$valor]) }}
            @else
                nenhum horário ocupado
            @endif
        </p>
    @endforeach

    @auth
        <h2>Reservar esta sala</h2>
        <form action="/reservas" method="POST">
            @csrf
            <input type="hidden" name="sala_id" value="{{ $sala->id }}">

            <p><strong>Dias (marque um ou mais):</strong></p>
            @foreach ($dias as $valor => $rotulo)
                <label>
                    <input type="checkbox" name="dias[]" value="{{ $valor }}">
                    {{ $rotulo }}
                </label><br>
            @endforeach

            <p><strong>Horários (escolha até 3):</strong></p>
            @foreach ($blocos as $bloco)
                <label>
                    <input type="checkbox" name="blocos[]" value="{{ $bloco }}">
                    {{ $bloco }}
                </label><br>
            @endforeach

            <button type="submit">Reservar</button>
        </form>
    @else
        <p><a href="/login">Faça login</a> para reservar.</p>
    @endauth
</body>
</html>