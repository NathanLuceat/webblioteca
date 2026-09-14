<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">Meus dados</h3>
                <p><strong>Nome:</strong> {{ auth()->user()->name }}</p>
                <p><strong>E-mail:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Membro desde:</strong> {{ auth()->user()->created_at->format('d/m/Y') }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">Resumo</h3>
                <p>Empréstimos ativos: {{ $emprestimosAtivos }}</p>
                <p>Reservas ativas: {{ $reservasAtivas }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">Ações rápidas</h3>
                <p>
                    <a href="/livros"><button type="button">Catálogo de Livros</button></a>
                    <a href="/meus-emprestimos"><button type="button">Meus Empréstimos</button></a>
                    <a href="/salas"><button type="button">Salas</button></a>
                    <a href="/minhas-reservas"><button type="button">Minhas Reservas</button></a>
                </p>
            </div>

        </div>
    </div>
</x-app-layout>