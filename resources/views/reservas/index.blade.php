<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Reservas — Webblioteca</title>
</head>
<body>
    <a href="/salas">&larr; Salas</a>
    <h1>Minhas Reservas</h1>

    @if (session('sucesso'))
        <p style="color: green;">{{ session('sucesso') }}</p>
    @endif

    <ul>
        @forelse ($reservas as $reserva)
            <li>
                {{ $reserva->sala->nome }} — {{ $reserva->data }}
                ({{ $reserva->hora_inicio }} às {{ $reserva->hora_fim }})

                <form action="/reservas/{{ $reserva->id }}/cancelar" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Cancelar</button>
                </form>
            </li>
        @empty
            <li>Você não tem nenhuma reserva.</li>
        @endforelse
    </ul>
</body>
</html>