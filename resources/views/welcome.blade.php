<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>WeBBlioteca — A Grande Biblioteca Virtual</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=fraunces:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&display=swap" rel="stylesheet" />

        <!-- Anti-FOUC: sincroniza o tema ANTES do render -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">

            <!-- Navegação da landing -->
            <header class="sticky top-0 z-40 bg-paper-card/90 border-b border-line backdrop-blur-sm">
                <div class="h-1 bg-gradient-to-r from-leather via-brass to-folio"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <a href="/" class="flex items-center gap-2.5">
                        <x-application-logo class="block h-8 w-auto fill-current text-leather" />
                        <span class="font-display text-lg tracking-tight text-ink">
                            We<span class="italic text-leather">BB</span>lioteca
                        </span>
                    </a>

                    <nav class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary text-xs">Minha estante</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline text-xs">Entrar</a>
                            <a href="{{ route('register') }}" class="btn btn-primary text-xs">Cadastrar-se</a>
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Hero -->
            <section class="relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 grid gap-12 lg:grid-cols-2 items-center">
                    <div>
                        <p class="label-overline mb-4">Biblioteca · Empréstimos · Estudo</p>
                        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-semibold text-ink tracking-tight leading-[1.05]">
                            Entre na estante<br/>
                            <span class="italic text-leather">sem precisar</span> pousar o livro
                        </h1>
                        <p class="mt-6 text-lg text-ink-soft max-w-xl leading-relaxed">
                            Catálogo completo de obras, empréstimo em 7 dias e salas de estudo
                            silenciosas — tudo organizado como uma boa ficha de biblioteca.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir para a estante</a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary">Fazer seu cartão de leitor</a>
                                <a href="{{ route('login') }}" class="btn btn-outline">Entrar</a>
                            @endauth
                        </div>
                    </div>

                    <!-- Ilustração: prateleiras -->
                    <div class="hidden lg:block" aria-hidden="true">
                        <div class="panel-frame">
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="book-spine binding-leather rounded aspect-[3/4] flex flex-col justify-between p-4">
                                    <div class="w-10 h-px bg-brass-light/70"></div>
                                    <div>
                                        <p class="text-paper-light font-display italic text-lg leading-snug">O Velho e o Mar</p>
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-paper-light/70 mt-1">Hemingway</p>
                                    </div>
                                </div>
                                <div class="book-spine binding-forest rounded aspect-[3/4] flex flex-col justify-between p-4">
                                    <div class="w-10 h-px bg-brass-light/70"></div>
                                    <div>
                                        <p class="text-paper-light font-display italic text-lg leading-snug">Dom Casmurro</p>
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-paper-light/70 mt-1">Machado de Assis</p>
                                    </div>
                                </div>
                                <div class="book-spine binding-brass rounded aspect-[3/4] flex flex-col justify-between p-4">
                                    <div class="w-10 h-px bg-paper-light/60"></div>
                                    <div>
                                        <p class="text-paper-light font-display italic text-lg leading-snug">A Metamorfose</p>
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-paper-light/70 mt-1">Kafka</p>
                                    </div>
                                </div>
                                <div class="book-spine binding-berry rounded aspect-[3/4] flex flex-col justify-between p-4">
                                    <div class="w-10 h-px bg-brass-light/70"></div>
                                    <div>
                                        <p class="text-paper-light font-display italic text-lg leading-snug">Grande Sertão</p>
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-paper-light/70 mt-1">Guimarães Rosa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Regra ornamental -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="rule-ornament">
                    <span class="ornament-mark">❦</span>
                </div>
            </div>

            <!-- Recursos -->
            <section class="py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-6 md:grid-cols-3">
                    <div class="card paper-grain">
                        <div class="p-6 sm:p-8">
                            <div class="w-12 h-12 rounded-full bg-leather/10 border border-leather/20 flex items-center justify-center mb-5">
                                <svg class="w-6 h-6 text-leather" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h3 class="font-display text-xl font-semibold text-ink mb-2">Empréstimo de obras</h3>
                            <p class="text-sm text-ink-soft leading-relaxed">
                                Escolha um exemplar disponível, leve para casa por 7 dias e devolva
                                quando terminar — como uma verdadeira ficha de empréstimo.
                            </p>
                        </div>
                    </div>

                    <div class="card paper-grain">
                        <div class="p-6 sm:p-8">
                            <div class="w-12 h-12 rounded-full bg-folio/10 border border-folio/20 flex items-center justify-center mb-5">
                                <svg class="w-6 h-6 text-folio" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-display text-xl font-semibold text-ink mb-2">Reserva de salas</h3>
                            <p class="text-sm text-ink-soft leading-relaxed">
                                Agende blocos de 1h nas salas de estudo, das 06h às 18h,
                                para hoje, amanhã ou depois de amanhã.
                            </p>
                        </div>
                    </div>

                    <div class="card paper-grain">
                        <div class="p-6 sm:p-8">
                            <div class="w-12 h-12 rounded-full bg-brass/10 border border-brass/20 flex items-center justify-center mb-5">
                                <svg class="w-6 h-6 text-brass-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h3 class="font-display text-xl font-semibold text-ink mb-2">Cadastro de leitor</h3>
                            <p class="text-sm text-ink-soft leading-relaxed">
                                Crie seu registro em segundos e acompanhe empréstimos e reservas
                                em um só lugar, na sua estante pessoal.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Rodapé -->
            <footer class="mt-auto border-t border-line bg-paper-alt/70">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-ink-soft">
                        <span class="font-display italic text-brass-deep">❦</span>
                        <span class="text-sm font-display text-ink-soft">Grande Biblioteca Virtual — catalogada com zelo</span>
                    </div>
                    <p class="text-xs text-ink-faint">WeBBlioteca · papel, couro e latão © {{ date('Y') }}</p>
                </div>
            </footer>
        </div>
    </body>
</html>