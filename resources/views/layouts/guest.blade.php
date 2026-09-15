<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WeBBlioteca') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=fraunces:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="min-h-screen lg:grid lg:grid-cols-12">

            <!-- Painel de couro (identidade) -->
            <aside class="hidden lg:flex lg:col-span-5 xl:col-span-4 relative flex-col justify-between p-10 text-paper-light overflow-hidden"
                   style="background: radial-gradient(120% 90% at 115% -8%, rgba(169,127,47,.22), transparent 55%), linear-gradient(150deg, #6E2515 0%, #4E1A0E 58%, #3A1209 100%);">
                <!-- Grão do couro -->
                <div class="absolute inset-0 opacity-[0.06] pointer-events-none"
                     style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27160%27 height=%27160%27%3E%3Cfilter id=%27g%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%272%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23g)%27/%3E%3C/svg%3E')"></div>
                <!-- Molduras de filete -->
                <div class="absolute inset-5 border border-paper-light/20 pointer-events-none"></div>
                <div class="absolute inset-[22px] border border-paper-light/10 pointer-events-none"></div>

                <!-- Topo: marca -->
                <div class="relative flex items-start gap-3">
                    <x-application-logo class="h-11 w-auto drop-shadow" />
                    <div>
                        <p class="font-display text-2xl leading-none tracking-tight text-paper-light">We<span class="italic text-brass-light">BB</span>lioteca</p>
                        <p class="mt-1 text-[11px] uppercase tracking-[0.28em] text-paper-light/60">Biblioteca Virtual</p>
                    </div>
                </div>

                <!-- Meio: citação -->
                <div class="relative my-8">
                    <div class="w-10 h-px bg-brass-light/70 mb-6"></div>
                    <p class="font-display italic text-2xl leading-relaxed text-paper-light/90">
                        “Um bom livro é um amigo que<br/>nunca nos abandona.”
                    </p>
                    <p class="mt-4 text-xs uppercase tracking-[0.22em] text-paper-light/55">— para os velhos e novos leitores</p>
                </div>

                <!-- Rodapé do painel -->
                <div class="relative flex items-center justify-between text-[11px] uppercase tracking-[0.2em] text-paper-light/45">
                    <span>Est. MMXXIV</span>
                    <span class="text-brass-light/75">❦ papel · couro · latão</span>
                </div>
            </aside>

            <!-- Coluna de papel (formulário) -->
            <div class="lg:col-span-7 xl:col-span-8 relative flex flex-col justify-center min-h-screen px-6 py-12 sm:px-12 lg:px-20">
                <!-- Marca no mobile -->
                <div class="flex lg:hidden items-center gap-2.5 mb-8 justify-center">
                    <x-application-logo class="h-9 w-auto text-leather" />
                    <span class="font-display text-xl text-ink">We<span class="italic text-leather">BB</span>lioteca</span>
                </div>

                <div class="w-full max-w-md mx-auto">
                    <div class="mb-7">
                        <div class="label-overline mb-2">Acesso à estante</div>
                        <div class="rule-ornament">
                            <span class="ornament-mark">❦</span>
                        </div>
                    </div>

                    <div class="card paper-grain">
                        <div class="p-7 sm:p-9">
                            {{ $slot }}
                        </div>
                    </div>

                    <p class="mt-6 text-center text-xs text-ink-faint">
                        Dúvidas? Fale com o bibliotecário <a href="mailto:biblioteca@example.test" class="text-leather hover:text-leather-deep underline underline-offset-2">biblioteca@example.test</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>