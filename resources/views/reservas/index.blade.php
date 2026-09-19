<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-7 bg-leather rounded-full"></div>
            <div>
                <p class="label-overline mb-1">Agenda das salas</p>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink tracking-tight leading-none">
                    Minhas Reservas
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('sucesso'))
                <div class="card border-folio/40 bg-folio/5 paper-grain mb-6">
                    <div class="px-5 py-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-folio shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-folio font-medium">{{ session('sucesso') }}</p>
                    </div>
                </div>
            @endif

            @forelse ($reservas as $reserva)
                @php
                    $dataReserva = \Illuminate\Support\Carbon::parse($reserva->data);
                    $hoje = $dataReserva->isToday();
                @endphp
                <article class="card paper-grain mb-5">
                    <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="book-spine binding-forest w-2 self-stretch sm:self-auto" aria-hidden="true"></div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-display text-lg font-semibold text-ink leading-tight">
                                {{ $reserva->sala->nome }}
                            </h3>
                            <p class="text-sm text-ink-soft mt-0.5">
                                {{ $dataReserva->format('d/m/Y') }}
                                <span class="text-line-strong mx-2">·</span>
                                {{ \Illuminate\Support\Carbon::parse($reserva->hora_inicio)->format('H:i') }}
                                às
                                {{ \Illuminate\Support\Carbon::parse($reserva->hora_fim)->format('H:i') }}
                            </p>
                        </div>

                        <div class="flex flex-col sm:items-end gap-3 sm:min-w-[200px]">
                            @if ($hoje)
                                <span class="stamp stamp-warn">Hoje</span>
                            @else
                                <span class="stamp stamp-neutral">Agendada</span>
                            @endif
                            <form action="{{ route('reservas.cancelar', $reserva->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Cancelar esta reserva?')"
                                        class="btn btn-ghost w-full text-xs dark:text-leather">
                                    Cancelar reserva
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="card paper-grain">
                    <div class="p-12 text-center">
                        <p class="font-display italic text-2xl text-ink-soft mb-1">Nada agendado</p>
                        <p class="text-sm text-ink-faint mb-6">Você não tem nenhuma reserva de sala.</p>
                        <a href="{{ route('salas.index') }}" class="btn btn-primary">Ver salas de estudo</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>