<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="paper-grain card p-8">
                <p class="label-overline">Cadastro temporário</p>
                <h1 class="font-display text-3xl font-semibold tracking-tight text-ink mt-2">Cadastrar livro</h1>
                <p class="text-ink-soft text-sm mt-2">O livro e seus exemplares serão removidos automaticamente após 24 horas.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-sm border-l-4 border-leather-deep bg-leather/10 px-4 py-3 text-sm text-leather">
                        <p class="font-semibold">Verifique os campos abaixo.</p>
                        <ul class="mt-1 list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.livros.store') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="titulo" class="label-overline">Título</label>
                        <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required autofocus
                            class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="autor" class="label-overline">Autor</label>
                            <input type="text" id="autor" name="autor" value="{{ old('autor') }}" required
                                class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                        </div>

                        <div>
                            <label for="categoria" class="label-overline">Categoria</label>
                            <input type="text" id="categoria" name="categoria" value="{{ old('categoria') }}" required
                                class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="ano_publicacao" class="label-overline">Ano de publicação</label>
                            <input type="number" id="ano_publicacao" name="ano_publicacao" value="{{ old('ano_publicacao') }}" min="1000" max="{{ date('Y') + 1 }}" required
                                class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                        </div>

                        <div>
                            <label for="qtd_exemplares" class="label-overline">Quantidade de exemplares</label>
                            <input type="number" id="qtd_exemplares" name="qtd_exemplares" value="{{ old('qtd_exemplares', 1) }}" min="1" max="50" required
                                class="mt-1 block w-full rounded-[3px] border-line-strong bg-paper-light px-3 py-2 text-sm text-ink placeholder-ink-soft focus:border-brass focus:ring-brass" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.painel') }}" class="btn btn-outline text-xs">Voltar ao painel</a>
                        <button type="submit" class="btn btn-primary text-xs">Cadastrar livro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>