<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="paper-grain card p-8">
                <p class="label-overline">Todos os usuários</p>
                <h1 class="font-display text-3xl font-semibold tracking-tight text-ink mt-2">Reservas de salas</h1>
                <p class="text-ink-soft text-sm mt-2">Relação completa de reservas cadastradas no sistema.</p>

                @if (session('sucesso'))
                    <div class="mt-6 rounded-sm border-l-4 border-brass bg-paper-light/60 px-4 py-3 text-sm text-ink">
                        {{ session('sucesso') }}
                    </div>
                @endif

                <div class="mt-8 -mx-4 overflow-x-auto sm:mx-0">
                    @forelse ($reservas as $reserva)
                        <div class="card mb-4 p-5 flex flex-wrap items-center gap-4">
                            <div class="flex-1 min-w-[200px]">
                                <p class="font-display text-base font-semibold text-ink">
                                    {{ $reserva->usuario->name }}
                                    <span class="text-ink-soft text-xs font-normal">· {{ $reserva->usuario->email }}</span>
                                </p>
                                <p class="text-ink-soft text-sm mt-0.5">{{ $reserva->sala->nome }}</p>
                            </div>
                            <div class="text-right text-sm">
                                <p class="text-ink font-medium">{{ \Carbon\Carbon::parse($reserva->data)->format('d/m/Y') }}</p>
                                <p class="text-ink-soft mt-0.5">{{ $reserva->hora_inicio }} — {{ $reserva->hora_fim }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-sm border border-dashed border-line-strong bg-paper-light/40 px-6 py-12 text-center">
                            <p class="text-ink-soft text-sm">Nenhuma reserva cadastrada ainda.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>