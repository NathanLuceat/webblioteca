<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-7 bg-leather rounded-full"></div>
            <div>
                <p class="label-overline mb-1">Sua estante</p>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink tracking-tight leading-none">
                    Bem-vindo(a), {{ auth()->user()->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid gap-6 lg:grid-cols-3">

                <!-- Meus dados -->
                <section class="card paper-grain lg:col-span-2">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-start gap-4">
                            <div class="hidden sm:block">
                                <div class="w-14 h-14 rounded bg-leather/10 border border-leather/20 flex items-center justify-center">
                                    <span class="font-display text-2xl font-semibold text-leather">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-display text-lg font-semibold text-ink mb-4">Ficha do leitor</h3>
                                <dl class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <dt class="label-overline mb-1">Nome completo</dt>
                                        <dd class="text-ink">{{ auth()->user()->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="label-overline mb-1">E-mail</dt>
                                        <dd class="text-ink-soft">{{ auth()->user()->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="label-overline mb-1">Membro desde</dt>
                                        <dd class="text-ink">{{ auth()->user()->created_at->format('d/m/Y') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="label-overline mb-1">Status</dt>
                                        <dd>
                                            <span class="stamp stamp-ok">Ativo</span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Resumo -->
                <section class="card paper-grain">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-display text-lg font-semibold text-ink mb-4">Balancete</h3>
                        <dl class="space-y-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded bg-folio/10 border border-folio/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-folio" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <span class="text-ink-soft">Empréstimos ativos</span>
                                </div>
                                <span class="font-display text-2xl font-semibold text-ink">{{ $emprestimosAtivos }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded bg-brass/10 border border-brass/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-brass-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-ink-soft">Reservas ativas</span>
                                </div>
                                <span class="font-display text-2xl font-semibold text-ink">{{ $reservasAtivas }}</span>
                            </div>
                        </dl>
                    </div>
                </section>

            </div>

            <!-- Ações rápidas -->
            <section class="mt-6 card paper-grain">
                <div class="p-6 sm:p-8">
                    <h3 class="font-display text-lg font-semibold text-ink mb-5">Atalhos da biblioteca</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <a href="{{ route('livros.index') }}" class="group flex items-center gap-4 p-4 bg-paper-light border border-line hover:border-brass/50 hover:bg-brass/5 transition-colors rounded-[3px] dark:bg-paper-light/10 dark:border-line-soft">
                            <div class="w-11 h-11 rounded bg-leather/10 border border-leather/20 flex items-center justify-center group-hover:bg-leather/15 transition-colors">
                                <svg class="w-6 h-6 text-leather" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-display font-semibold text-ink">Catálogo de Livros</p>
                                <p class="text-xs text-ink-soft mt-0.5">Explorar o acervo</p>
                            </div>
                        </a>

                        <a href="{{ route('emprestimos.index') }}" class="group flex items-center gap-4 p-4 bg-paper-light border border-line hover:border-brass/50 hover:bg-brass/5 transition-colors rounded-[3px] dark:bg-paper-light/10 dark:border-line-soft">
                            <div class="w-11 h-11 rounded bg-folio/10 border border-folio/20 flex items-center justify-center group-hover:bg-folio/15 transition-colors">
                                <svg class="w-6 h-6 text-folio" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-display font-semibold text-ink">Meus Empréstimos</p>
                                <p class="text-xs text-ink-soft mt-0.5">Verificar prazos</p>
                            </div>
                        </a>

                        <a href="{{ route('salas.index') }}" class="group flex items-center gap-4 p-4 bg-paper-light border border-line hover:border-brass/50 hover:bg-brass/5 transition-colors rounded-[3px] dark:bg-paper-light/10 dark:border-line-soft">
                            <div class="w-11 h-11 rounded bg-brass/10 border border-brass/20 flex items-center justify-center group-hover:bg-brass/15 transition-colors">
                                <svg class="w-6 h-6 text-brass-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-display font-semibold text-ink">Salas de Estudo</p>
                                <p class="text-xs text-ink-soft mt-0.5">Reservar horários</p>
                            </div>
                        </a>

                        <a href="{{ route('reservas.index') }}" class="group flex items-center gap-4 p-4 bg-paper-light border border-line hover:border-brass/50 hover:bg-brass/5 transition-colors rounded-[3px] dark:bg-paper-light/10 dark:border-line-soft">
                            <div class="w-11 h-11 rounded bg-leather/10 border border-leather/20 flex items-center justify-center group-hover:bg-leather/15 transition-colors">
                                <svg class="w-6 h-6 text-leather" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-display font-semibold text-ink">Minhas Reservas</p>
                                <p class="text-xs text-ink-soft mt-0.5">Gerenciar agendas</p>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>