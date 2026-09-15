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
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>