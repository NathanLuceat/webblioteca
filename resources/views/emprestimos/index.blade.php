<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-7 bg-leather rounded-full"></div>
            <div>
                <p class="label-overline mb-1">Livros em sua posse</p>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink tracking-tight leading-none">
                    Meus Empréstimos
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

            @forelse ($emprestimos as $emprestimo)
                @php
                    $prevista = \Illuminate\Support\Carbon::parse($emprestimo->data_prevista_devolucao);
                    $diasRestantes = now()->startOfDay()->diffInDays($prevista->copy()->startOfDay(), false);
                    $emDia = $diasRestantes >= 0;
                @endphp
                <article class="card paper-grain mb-5">
                    <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="book-spine binding-leather w-2 self-stretch sm:self-auto" aria-hidden="true"></div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-display text-lg font-semibold text-ink leading-tight">
                                {{ $emprestimo->exemplar->livro->titulo }}
                            </h3>
                            <p class="text-sm text-ink-soft mt-0.5">
                                <span class="font-mono text-[13px]">{{ $emprestimo->exemplar->codigo_patrimonio }}</span>
                                <span class="text-line-strong mx-2">·</span>
                                emprestado em {{ \Illuminate\Support\Carbon::parse($emprestimo->data_emprestimo)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="flex flex-col sm:items-end gap-3 sm:min-w-[200px]">
                            <span class="stamp {{ $emDia ? 'stamp-ok' : 'stamp-danger' }}">
                                devolução: {{ $prevista->format('d/m/Y') }}
                            </span>
                            <span class="text-xs text-ink-faint">
                                {{ $emDia ? $diasRestantes . ' dia(s) restante(s)' : abs($diasRestantes) . ' dia(s) em atraso' }}
                            </span>
                            <form action="{{ route('emprestimos.devolver', $emprestimo->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline w-full text-xs">Devolver</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="card paper-grain">
                    <div class="p-12 text-center">
                        <p class="font-display italic text-2xl text-ink-soft mb-1">Estante vazia por enquanto</p>
                        <p class="text-sm text-ink-faint mb-6">Você não tem nenhum empréstimo ativo.</p>
                        <a href="{{ route('livros.index') }}" class="btn btn-primary">Explorar o catálogo</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>