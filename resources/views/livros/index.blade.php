<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-7 bg-leather rounded-full"></div>
            <div>
                <p class="label-overline mb-1">Acervo</p>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink tracking-tight leading-none">
                    Catálogo de Livros
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex items-center justify-between gap-4">
                <p class="text-sm text-ink-soft">
                    {{ $livros->count() }} {{ $livros->count() === 1 ? 'título' : 'títulos' }} disponíveis para consulta
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($livros as $livro)
                    @php
                        $encadernacoes = ['binding-leather', 'binding-forest', 'binding-brass', 'binding-berry'];
                        $encadernacao = $encadernacoes[$loop->index % count($encadernacoes)];
                    @endphp
                    <article class="card paper-grain">
                        <div class="p-5 sm:p-6">
                            <div class="flex gap-4">
                                <!-- Lombada decorativa -->
                                <div class="book-spine {{ $encadernacao }} w-2.5 shrink-0" aria-hidden="true"></div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <h3 class="font-display text-lg font-semibold text-ink leading-tight">
                                            <a href="{{ route('livros.show', $livro->id) }}" class="hover:text-leather transition-colors">
                                                {{ $livro->titulo }}
                                            </a>
                                        </h3>
                                    </div>

                                    @if($livro->categoria)
                                        <span class="stamp stamp-neutral mb-3">{{ $livro->categoria }}</span>
                                    @endif

                                    <dl class="space-y-1.5 text-sm mt-3">
                                        <div class="flex items-center gap-2">
                                            <dt class="text-ink-faint shrink-0">Autor:</dt>
                                            <dd class="text-ink-soft">{{ $livro->autor }}</dd>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <dt class="text-ink-faint shrink-0">Ano:</dt>
                                            <dd class="text-ink-soft">{{ $livro->ano_publicacao }}</dd>
                                        </div>
                                    </dl>

                                    <div class="mt-4 pt-4 border-t border-line-soft flex items-center justify-between">
                                        <a href="{{ route('livros.show', $livro->id) }}" class="text-sm font-display font-semibold text-leather hover:text-leather-deep flex items-center gap-1.5 transition-colors">
                                            Ver exemplares
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
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