<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-7 bg-leather rounded-full"></div>
            <div>
                <p class="label-overline mb-1">Leitura silenciosa</p>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink tracking-tight leading-none">
                    Salas de Estudo
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-sm text-ink-soft">
                    Reserve até 3 blocos de 1h por dia, entre as 06:00 e as 18:00.
                </p>
                <a href="{{ route('reservas.index') }}" class="btn btn-outline text-xs">
                    Minhas Reservas
                </a>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($salas as $sala)
                    @php
                        $encadernacoes = ['binding-forest', 'binding-brass', 'binding-leather', 'binding-berry', 'binding-slate'];
                        $encadernacao = $encadernacoes[$loop->index % count($encadernacoes)];
                    @endphp
                    <article class="card paper-grain">
                        <div class="p-5 sm:p-6">
                            <div class="flex gap-4">
                                <div class="book-spine {{ $encadernacao }} w-2 shrink-0" aria-hidden="true"></div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-display text-lg font-semibold text-ink leading-tight">
                                        <a href="{{ route('salas.show', $sala->id) }}" class="hover:text-leather transition-colors dark:text-ink">
                                            {{ $sala->nome }}
                                        </a>
                                    </h3>

                                    <dl class="space-y-1.5 text-sm mt-3">
                                        <div class="flex items-center gap-2">
                                            <dt class="text-ink-faint shrink-0">Capacidade:</dt>
                                            <dd class="text-ink-soft">
                                                <span class="inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-brass-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    {{ $sala->capacidade }} pessoas
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <dt class="text-ink-faint shrink-0">Localização:</dt>
                                            <dd class="text-ink-soft">{{ $sala->localizacao }}</dd>
                                        </div>
                                    </dl>

                                    <div class="mt-4 pt-4 border-t border-line-soft">
                                        <a href="{{ route('salas.show', $sala->id) }}" class="btn btn-outline w-full text-xs dark:!text-ink-faint">
                                            Reservar horário
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>