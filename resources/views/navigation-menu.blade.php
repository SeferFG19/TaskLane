<nav x-data="{ open: false }" class="app-navbar" role="navigation" aria-label="Menú principal">
    <!-- Primary Navigation Menu -->
    <div class="app-navbar-inner">
        <div class="app-nav-left">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2" aria-label="Ir al dashboard">
                <x-application-mark class="block h-8 w-auto" />
                <span class="app-logo-text">Proyectos</span>
            </a>
        </div>

        <div class="app-nav-right hidden sm:flex sm:items-center sm:ms-6">
            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    aria-label="Abrir menú de equipos"
                                    class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium text-gray-500 bg-white hover:text-gray-700">
                                    {{ Auth::user()->currentTeam->name }}
                                    <svg class="ms-2 -me-0.5 size-4" ...></svg>
                                </button>
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <div class="w-60" role="menu" aria-label="Opciones del equipo">
                                <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>
                                <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                    Team Settings
                                </x-dropdown-link>

                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                    <x-dropdown-link href="{{ route('teams.create') }}">Create New Team</x-dropdown-link>
                                @endcan

                                @if (Auth::user()->allTeams()->count() > 1)
                                    <div class="border-t border-gray-200"></div>
                                    <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-switchable-team :team="$team"/>
                                    @endforeach
                                @endif
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            <!-- Settings Dropdown -->
            <div class="ms-3 relative">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button aria-haspopup="true"
                                aria-expanded="false"
                                aria-label="Abrir menú de usuario"
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none">
                                <img class="size-8 rounded-full object-cover"
                                     src="{{ Auth::user()->profile_photo_url }}"
                                     alt="Foto de perfil de {{ Auth::user()->name }}" />
                            </button>
                        @else
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    aria-label="Abrir menú de usuario"
                                    class="inline-flex items-center px-3 py-2 text-sm text-gray-500 bg-white">
                                    {{ Auth::user()->name }}
                                    <svg class="ms-2 -me-0.5 size-4" ...></svg>
                                </button>
                            </span>
                        @endif
                    </x-slot>

                    <x-slot name="content">
                        <div role="menu" aria-label="Opciones de usuario">
                            <div class="block px-4 py-2 text-xs text-gray-400">Mi cuenta</div>

                            <x-dropdown-link href="{{ route('profile.show') }}">Perfil</x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">API Tokens</x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();">
                                    Cerrar sesión
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        <!-- Mobile Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open"
                aria-controls="mobile-menu"
                aria-expanded="false"
                aria-label="Abrir menú móvil"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400">
                <svg class="size-6" ...></svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div id="mobile-menu" :class="{'block': open, 'hidden': ! open}"
        class="hidden sm:hidden bg-white border-t border-gray-200" role="menu">
        
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Proyectos
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4" aria-label="Información de usuario">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full"
                             src="{{ Auth::user()->profile_photo_url }}"
                             alt="Foto de perfil de {{ Auth::user()->name }}" />
                    </div>
                @endif
                <div>
                    <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>
    </div>
</nav>
