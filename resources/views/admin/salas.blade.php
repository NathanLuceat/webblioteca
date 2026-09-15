<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="paper-grain card p-8">
                <p class="label-overline">Cadastro temporário</p>
                <h1 class="font-display text-3xl font-semibold tracking-tight text-ink mt-2">Cadastrar sala</h1>
                <p class="text-ink-soft text-sm mt-2">A sala será removida automaticamente após 24 horas.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-sm border-l-4 border-red-700 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <p class="font-semibold">Verifique os campos abaixo.</p>
                        <ul class="mt-1 list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.salas.store') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="nome" class="label-overline">Nome da sala</label>
                        <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required autofocus
                            class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="capacidade" class="label-overline">Capacidade</label>
                            <input type="number" id="capacidade" name="capacidade" value="{{ old('capacidade') }}" min="1" max="500" required
                                class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                        </div>

                        <div>
                            <label for="localizacao" class="label-overline">Localização</label>
                            <input type="text" id="localizacao" name="localizacao" value="{{ old('localizacao') }}" required
                                class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.painel') }}" class="btn btn-outline text-xs">Voltar ao painel</a>
                        <button type="submit" class="btn btn-primary text-xs">Cadastrar sala</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>