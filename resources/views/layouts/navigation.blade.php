<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('image/30.png') }}" alt="Gen Prime" class="block h-16 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if (auth()->user()->isRole('head'))
                        <x-nav-link :href="route('head.reports.index')" :active="request()->routeIs('head.reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endif
                    @if (Auth::user()->isRole('admin'))
                        @php
                            // Helper: aktifkan parent ketika salah satu child aktif
                            $verifyActive = request()->routeIs('admin.agents.*') || request()->routeIs('admin.sales.*');
                            $masterActive = request()->routeIs('users.*') || request()->routeIs('products.*');
                        @endphp
                        <x-nav-link :href="route('contests.index')" :active="request()->routeIs('contests.*')">
                            <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Kontes
                        </x-nav-link>
                        <x-nav-link :href="route('galleries.index')" :active="request()->routeIs('galleries.*')">
                            <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{ __('Galeri') }}
                        </x-nav-link>
                        <x-nav-link :href="route('collaborations.index')" :active="request()->routeIs('collaborations.*')">
                            <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Collaboration
                        </x-nav-link>
                        <x-nav-link :href="route('targets-penjualan.index')" :active="request()->routeIs('targets-penjualan.*')">
                            <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{ __('Target Penjualan') }}
                        </x-nav-link>
                        {{-- <x-nav-link :href="route('event-targets.index')" :active="request()->routeIs('event-targets.*')">
                            {{ __('Event Targets') }}
                        </x-nav-link> --}}
                        <div class="hidden sm:flex items-center gap-2" x-data="{ openVerif: false, openMaster: false }">
                            {{-- Verifikasi (dropdown) --}}
                            <div class="relative">
                                <button @click="openVerif = !openVerif; openMaster = false"
                                    @click.away="openVerif = false"
                                    class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium
                       {{ $verifyActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300' }}
                       hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none">
                                    <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    Verifikasi
                                    <svg class="ms-1 h-4 w-4 transition-transform"
                                        :class="openVerif ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="openVerif"
                                    class="absolute z-40 mt-2 w-56 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow"
                                    x-transition>
                                    <a href="{{ route('admin.agents.index') }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800
                          {{ request()->routeIs('admin.agents.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-200' }}">
                                        Verifikasi Agent
                                    </a>
                                    <a href="{{ route('admin.sales.index') }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800
                          {{ request()->routeIs('admin.sales.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-200' }}">
                                        Verifikasi Penjualan
                                    </a>
                                </div>
                            </div>

                            {{-- Master (dropdown) --}}
                            <div class="relative">
                                <button @click="openMaster = !openMaster; openVerif = false"
                                    @click.away="openMaster = false"
                                    class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium
                       {{ $masterActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300' }}
                       hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none">
                                    <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 7h18M3 12h18M3 17h18" />
                                    </svg>
                                    Master
                                    <svg class="ms-1 h-4 w-4 transition-transform"
                                        :class="openMaster ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="openMaster"
                                    class="absolute z-40 mt-2 w-56 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow"
                                    x-transition>
                                    <a href="{{ route('users.index') }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800
                          {{ request()->routeIs('users.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-200' }}">
                                        Users
                                    </a>
                                    <a href="{{ route('products.index') }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800
                          {{ request()->routeIs('products.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-200' }}">
                                        Master Produk
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (auth()->user()->isRole('agent'))
                        <!-- Buat Target -->

                        <x-nav-link :href="route('agent.targets.index')" :active="request()->routeIs('agent.targets.index')">
                            <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{ __('Target Penjualan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('agent.contests.index')" :active="request()->routeIs('agent.contests.index')">
                            <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Kontes
                        </x-nav-link>
                        <!-- Lihat Daftar Target -->
                        {{-- <x-nav-link :href="route('sales-targets.index')" :active="request()->routeIs('sales-targets.index')">
                            {{ __('Target Penjualan') }}
                        </x-nav-link> --}}

                        <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
                            <svg class="h-4 w-4 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{ __('Penjualan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
