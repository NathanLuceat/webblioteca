<nav x-data="{ open: false }" class="bg-paper-card/90 border-b border-line backdrop-blur-sm sticky top-0 z-40">
    <!-- Filete duplo no topo -->
    <div class="h-1 bg-gradient-to-r from-leather via-brass to-folio"></div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        <x-application-logo class="block h-8 w-auto fill-current text-leather" />
                        <span class="font-display text-lg tracking-tight text-ink">
                            We<span class="italic text-leather">BB</span>lioteca
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Estante
                    </x-nav-link>
                    <x-nav-link :href="route('livros.index')" :active="request()->routeIs('livros.*')">
                        Catálogo
                    </x-nav-link>
                    <x-nav-link :href="route('salas.index')" :active="request()->routeIs('salas.*')">
                        Salas de Estudo
                    </x-nav-link>
                    <x-nav-link :href="route('emprestimos.index')" :active="request()->routeIs('emprestimos.index')">
                        Meus Empréstimos
                    </x-nav-link>
                    <x-nav-link :href="route('reservas.index')" :active="request()->routeIs('reservas.index')">
                        Minhas Reservas
                    </x-nav-link>
                    @auth
                        @if (Auth::user()->is_admin)
                            <x-nav-link :href="route('admin.painel')" :active="request()->routeIs('admin.*')">
                                Painel
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Theme Toggle + Perfil (logado) / Entrar (visitante) -->
            <div class="flex items-center gap-2 sm:ms-6">
                <!-- Botão Theme Toggle -->
                <button type="button"
                        x-data="{
                            dark: document.documentElement.classList.contains('dark'),
                            toggle() {
                                this.dark = !this.dark;
                                if (this.dark) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.theme = 'dark';
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.theme = 'light';
                                }
                            }
                        }"
                        @click="toggle()"
                        class="hidden sm:inline-flex p-2 rounded-[3px] border border-line-soft bg-paper-light/85 text-ink dark:text-ink-faint hover:text-ink dark:hover:text-ink-faint hover:border-line-strong shadow-stamp transition"
                        aria-label="Alternar tema">
                    <svg x-show="!dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
            </div>

            @auth
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <div class="flex items-center gap-2 px-3 py-2 border border-line-soft bg-paper-light/60 shadow-stamp text-sm text-ink-soft hover:text-ink hover:border-line-strong focus:outline-none transition ease-in-out duration-150 cursor-pointer">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-leather text-paper-light font-display text-xs font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>

                        <div class="border-t border-line-soft my-1"></div>

                        <x-dropdown-link :href="route('livros.index')">
                            Catálogo de Livros
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('salas.index')">
                            Salas de Estudo
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('emprestimos.index')">
                            Meus Empréstimos
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reservas.index')">
                            Minhas Reservas
                        </x-dropdown-link>

                        <div class="border-t border-line-soft my-1"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Sair da leitura
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            @guest
            <div class="hidden sm:flex sm:items-center gap-3 sm:ms-6">
                <a href="{{ route('login') }}" class="text-sm font-medium text-ink-soft hover:text-leather transition">Entrar</a>
                <a href="{{ route('register') }}" class="btn btn-primary text-xs">Criar cadastro</a>
            </div>
            @endguest

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-[3px] text-ink-soft hover:text-ink hover:bg-paper-alt focus:outline-none focus:bg-paper-alt focus:text-ink transition duration-150 ease-in-out" aria-label="Abrir menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-line-soft">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Estante
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('livros.index')" :active="request()->routeIs('livros.*')">
                Catálogo
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('salas.index')" :active="request()->routeIs('salas.*')">
                Salas de Estudo
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('emprestimos.index')" :active="request()->routeIs('emprestimos.index')">
                Meus Empréstimos
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservas.index')" :active="request()->routeIs('reservas.index')">
                Minhas Reservas
            </x-responsive-nav-link>
            @auth
                @if (Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.painel')" :active="request()->routeIs('admin.*')">
                        Painel
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-line-soft">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-display text-base font-semibold text-ink">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-ink-soft">{{ Auth::user()->email }}</div>
                </div>
                <!-- Theme Toggle Mobile -->
                <button type="button"
                        x-data="{
                            dark: document.documentElement.classList.contains('dark'),
                            toggle() {
                                this.dark = !this.dark;
                                if (this.dark) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.theme = 'dark';
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.theme = 'light';
                                }
                            }
                        }"
                        @click="toggle()"
                        class="sm:hidden p-2 rounded-[3px] border border-line-soft bg-paper-light/60 text-ink-soft dark:text-ink-faint hover:text-ink dark:hover:text-ink-faint hover:border-line-strong shadow-stamp transition"
                        aria-label="Alternar tema">
                    <svg x-show="!dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Perfil
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Sair da leitura
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth

        @guest
        <div class="pt-4 pb-3 border-t border-line-soft space-y-2">
            <a href="{{ route('login') }}" class="block w-full btn btn-outline text-xs text-center">Entrar</a>
            <a href="{{ route('register') }}" class="block w-full btn btn-primary text-xs text-center">Criar cadastro</a>
        </div>
        @endguest
    </div>
</nav>