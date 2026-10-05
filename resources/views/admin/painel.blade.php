<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="paper-grain card p-8">
                <p class="label-overline">Painel administrativo</p>
                <h1 class="font-display text-3xl font-semibold tracking-tight text-ink mt-2">Administração da Webblioteca</h1>
                <p class="text-ink-soft mt-2 text-sm">Gerencie o acervo e acompanhe as reservas. Livros e salas cadastrados aqui são temporários e expiram após 24 horas.</p>

                @if (session('sucesso'))
                    <div class="mt-6 rounded-sm border-l-4 border-brass bg-paper-light/60 px-4 py-3 text-sm text-ink">
                        {{ session('sucesso') }}
                    </div>
                @endif

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <a href="{{ route('admin.livros.form') }}" class="card p-6 hover:-translate-y-0.5 hover:shadow-emboss transition duration-200">
                        <div class="text-2xl">📚</div>
                        <h2 class="font-display text-lg font-semibold text-ink mt-3">Cadastrar livros</h2>
                        <p class="text-ink-soft text-sm mt-1">Adicione títulos ao acervo temporário com seus exemplares.</p>
                    </a>

                    <a href="{{ route('admin.salas.form') }}" class="card p-6 hover:-translate-y-0.5 hover:shadow-emboss transition duration-200">
                        <div class="text-2xl">🚪</div>
                        <h2 class="font-display text-lg font-semibold text-ink mt-3">Cadastrar salas</h2>
                        <p class="text-ink-soft text-sm mt-1">Registre salas de estudo para reserva.</p>
                    </a>

                    <a href="{{ route('admin.reservas') }}" class="card p-6 hover:-translate-y-0.5 hover:shadow-emboss transition duration-200">
                        <div class="text-2xl">🗓️</div>
                        <h2 class="font-display text-lg font-semibold text-ink mt-3">Ver reservas</h2>
                        <p class="text-ink-soft text-sm mt-1">Acompanhe todas as reservas de todos os usuários.</p>

                        <div class="mt-4">
                            <p class="label-overline mb-2">Últimos logs de reserva</p>
                            @if($recentReservationActivities->isEmpty())
                                <p class="text-ink-soft text-sm">Nenhuma reserva recente.</p>
                            @else
                                <div class="space-y-2">
                                    @foreach($recentReservationActivities as $activity)
                                        <div class="text-sm text-ink-soft flex items-start gap-2">
                                            <span class="mt-1 w-1.5 h-1.5 rounded-full bg-brass-deep"></span>
                                            <div class="min-w-0">
                                                <p class="text-ink font-medium">
                                                    {{ data_get($activity->summary, 'sala') }}
                                                </p>
                                                <p class="text-xs text-ink-soft">
                                                    {{ data_get($activity->summary, 'data') }} · {{ data_get($activity->summary, 'hora_inicio') }} — {{ data_get($activity->summary, 'hora_fim') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>