<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('salas.index') }}" class="inline-flex items-center gap-1.5 text-sm font-display font-semibold text-ink-soft hover:text-leather transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar às salas
        </a>
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-9 bg-leather rounded-full"></div>
            <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink tracking-tight leading-tight">
                {{ $sala->nome }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

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

            @if ($errors->any())
                <div class="card border-leather/40 bg-leather/5 paper-grain">
                    <div class="px-5 py-4">
                        <p class="text-sm font-semibold text-leather mb-1">Verifique os campos:</p>
                        <ul class="list-disc list-inside text-sm text-leather space-y-0.5">
                            @foreach ($errors->all() as $mensagem)
                                <li>{{ $mensagem }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-5">

                <!-- Ficha da sala -->
                <div class="lg:col-span-2 space-y-6">
                    <section class="card paper-grain">
                        <div class="p-6">
                            <h3 class="label-overline mb-1">Ficha da sala</h3>
                            <div class="rule-ornament mb-5"></div>
                            <dl class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <dt class="text-ink-faint text-sm shrink-0 w-24">Capacidade</dt>
                                    <dd class="text-ink flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-brass-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $sala->capacidade }} pessoas
                                    </dd>
                                </div>
                                <div class="flex items-center gap-2">
                                    <dt class="text-ink-faint text-sm shrink-0 w-24">Localização</dt>
                                    <dd class="text-ink-soft">{{ $sala->localizacao }}</dd>
                                </div>
                            </dl>
                        </div>
                    </section>

                    <!-- Horários ocupados -->
                    <section class="card paper-grain">
                        <div class="p-6">
                            <h3 class="label-overline mb-1">Ocupação</h3>
                            <div class="rule-ornament mb-5"></div>
                            @foreach ($dias as $valor => $rotulo)
                                <div class="mb-4 last:mb-0">
                                    <p class="text-sm font-display font-semibold text-ink mb-2">{{ $rotulo }}</p>
                                    @if (!empty($ocupados[$valor]))
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($ocupados[$valor] as $blocoOcupado)
                                                <span class="stamp stamp-danger">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ $blocoOcupado }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs italic text-ink-faint">nenhum horário ocupado</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                <!-- Formulário de reserva -->
                <div class="lg:col-span-3">
                    @auth
                        <section class="card paper-grain">
                            <div class="p-6 sm:p-8">
                                <h3 class="label-overline mb-1">Reserva</h3>
                                <div class="rule-ornament mb-6"></div>

                                <form action="{{ route('reservas.store') }}" method="POST"
                                      x-data="{
                                        diasSel: [],
                                        blocosSel: [],
                                        get contador() { return this.blocosSel.length; },
                                        get limiteOk() { return this.diasSel.length > 0 && this.blocosSel.length > 0; },
                                        indisponivel(bloco) { return this.contador >= 3 && !this.blocosSel.includes(bloco); }
                                      }">
                                    @csrf
                                    <input type="hidden" name="sala_id" value="{{ $sala->id }}">

                                    <!-- Dias -->
                                    <fieldset class="mb-7">
                                        <legend class="block font-display text-sm font-semibold text-ink mb-3">
                                            Escolha os dias <span class="text-ink-faint font-sans font-normal">(um ou mais)</span>
                                        </legend>
                                        <div class="grid gap-3 sm:grid-cols-3">
                                            @foreach ($dias as $valor => $rotulo)
                                                <label class="relative cursor-pointer group">
                                                    <input type="checkbox" name="dias[]" value="{{ $valor }}"
                                                           class="peer sr-only" x-model="diasSel">
                                                    <span class="block border border-line-strong bg-paper-light px-3 py-3 text-center text-sm text-ink-soft rounded-[3px] transition-colors dark:!text-ink-faint peer-checked:bg-folio peer-checked:text-paper-light peer-checked:border-folio dark:peer-checked:text-paper-light peer-focus-visible:ring-2 peer-focus-visible:ring-brass group-hover:border-brass/60">
                                                        {{ $rotulo }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>

                                    <!-- Blocos -->
                                    <fieldset class="mb-7">
                                        <legend class="block font-display text-sm font-semibold text-ink mb-1">
                                            Escolha os horários
                                        </legend>
                                        <p class="text-xs text-ink-faint mb-3">
                                            Até 3 blocos de 1h · <span class="text-brass-deep font-semibold" x-text="contador + ' de 3 selecionados'"></span>
                                        </p>
                                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                            @foreach ($blocos as $bloco)
                                                <label class="relative cursor-pointer group">
                                                    <input type="checkbox" name="blocos[]" value="{{ $bloco }}"
                                                           class="peer sr-only" x-model="blocosSel"
                                                           :disabled="indisponivel('{{ $bloco }}')">
                                                    <span class="block border border-line-strong bg-paper-light px-2 py-2.5 text-center text-[13px] font-mono text-ink-soft rounded-[3px] transition-colors dark:!text-ink-faint peer-checked:bg-leather peer-checked:text-paper-light peer-checked:border-leather dark:peer-checked:text-paper-light peer-disabled:opacity-35 peer-disabled:cursor-not-allowed peer-focus-visible:ring-2 peer-focus-visible:ring-brass group-hover:border-brass/60">
                                                        {{ $bloco }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>

                                    <div class="pt-5 border-t border-line-soft flex items-center justify-between gap-4">
                                        <p class="text-xs text-ink-faint">
                                            Confirme seus horários com atenção — reservas são individuais.
                                        </p>
                                        <button type="submit" class="btn btn-primary dark:!text-ink-faint" :disabled="!limiteOk"
                                                :class="limiteOk ? '' : 'opacity-40 cursor-not-allowed'">
                                            Reservar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    @else
                        <div class="card paper-grain">
                            <div class="p-8 text-center">
                                <p class="text-ink-soft mb-4">Você precisa estar logado para reservar uma sala de estudo.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary dark:!text-ink-faint">Entrar na biblioteca</a>
                            </div>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</x-app-layout>