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
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-line bg-paper-card/60 backdrop-blur-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                        {{ $header }}
                    </div>
                    <div class="rule-ornament max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-hidden="true">
                        <span class="ornament-mark">✳</span>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Rodapé -->
            <footer class="border-t border-line bg-paper-alt/70">
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