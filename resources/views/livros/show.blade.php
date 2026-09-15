<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('livros.index') }}" class="inline-flex items-center gap-1.5 text-sm font-display font-semibold text-ink-soft hover:text-leather transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar ao catálogo
        </a>
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-9 bg-leather rounded-full"></div>
            <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink tracking-tight leading-tight">
                {{ $livro->titulo }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Ficha do livro -->
            <section class="card paper-grain">
                <div class="p-6 sm:p-8 grid gap-8 md:grid-cols-[auto_1fr]">
                    <!-- Capa estilizada -->
                    <div class="hidden md:block">
                        <div class="panel-frame w-44">
                            <div class="book-spine binding-leather aspect-[2/3] flex flex-col justify-between p-4"
                                 style="background: linear-gradient(140deg, #7E2E1E, #5E1F13 70%, #451609);">
                                <div class="w-8 h-px bg-brass-light/70 mx-auto"></div>
                                <p class="text-center text-paper-light font-display italic text-base leading-snug px-1">
                                    {{ $livro->titulo }}
                                </p>
                                <p class="text-center text-[10px] uppercase tracking-[0.22em] text-paper-light/70">
                                    {{ $livro->autor }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="label-overline mb-1">Ficha bibliográfica</h3>
                        <div class="rule-ornament mb-5"></div>

                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="label-overline mb-1">Título</dt>
                                <dd class="text-ink font-display text-lg">{{ $livro->titulo }}</dd>
                            </div>
                            <div>
                                <dt class="label-overline mb-1">Autor</dt>
                                <dd class="text-ink">{{ $livro->autor }}</dd>
                            </div>
                            <div>
                                <dt class="label-overline mb-1">Categoria</dt>
                                <dd class="text-ink-soft">{{ $livro->categoria }}</dd>
                            </div>
                            <div>
                                <dt class="label-overline mb-1">Ano de publicação</dt>
                                <dd class="text-ink-soft">{{ $livro->ano_publicacao }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>

            <!-- Mensagens de sessão -->
            @if (session('sucesso'))
                <div class="card border-folio/40 bg-folio/5 paper-grain">
                    <div class="px-5 py-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-folio shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-folio font-medium">{{ session('sucesso') }}</p>
                    </div>
                </div>
            @endif

            @if (session('erro'))
                <div class="card border-leather/40 bg-leather/5 paper-grain">
                    <div class="px-5 py-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-leather shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-leather font-medium">{{ session('erro') }}</p>
                    </div>
                </div>
            @endif

            <!-- Exemplares -->
            <section>
                <div class="flex items-end justify-between mb-5">
                    <h3 class="font-display text-xl font-semibold text-ink">Exemplares</h3>
                    <span class="text-xs text-ink-faint">{{ $livro->exemplares->count() }} no acervo</span>
                </div>

                <div class="card paper-grain overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="table-folio">
                            <thead>
                                <tr>
                                    <th class="px-6">Código patrimonial</th>
                                    <th class="px-6">Estado</th>
                                    @auth
                                        <th class="px-6 text-right">Ação</th>
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($livro->exemplares as $exemplar)
                                    <tr>
                                        <td class="px-6 font-mono text-[13px] text-ink">{{ $exemplar->codigo_patrimonio }}</td>
                                        <td class="px-6">
                                            @if ($exemplar->status === 'disponivel')
                                                <span class="stamp stamp-ok">Disponível</span>
                                            @else
                                                <span class="stamp stamp-warn">Emprestado</span>
                                            @endif
                                        </td>
                                        @auth
                                            <td class="px-6 text-right">
                                                @if ($exemplar->status === 'disponivel')
                                                    <form action="{{ route('emprestimos.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="exemplar_id" value="{{ $exemplar->id }}">
                                                        <button type="submit" class="btn btn-green text-xs">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            Emprestar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-ink-faint italic">será devolvido em breve</span>
                                                @endif
                                            </td>
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->check() ? 3 : 2 }}" class="px-6 py-10 text-center text-ink-faint italic">
                                            Nenhum exemplar cadastrado para este título.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            @guest
                <p class="text-center text-sm text-ink-soft">
                    Faça <a href="{{ route('login') }}" class="text-leather hover:text-leather-deep underline underline-offset-2">login</a>
                    para solicitar empréstimos de exemplares disponíveis.
                </p>
            @endguest

        </div>
    </div>
</x-app-layout>