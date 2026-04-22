@php $level = strtolower(Auth::user()->level ?? ''); @endphp

<div x-data="{ openLogoutModal: false }">
    <aside
        :class="$store.sidebar.collapsed ? 'w-[72px]' : 'w-64'"
        class="fixed top-0 left-0 h-full bg-white dark:bg-gray-800 border-r border-slate-200 dark:border-gray-700 flex flex-col z-40 shadow-sm transition-all duration-300 ease-in-out overflow-hidden">

        {{-- Logo / App Name --}}
        <div class="flex items-center border-b border-slate-200 dark:border-gray-700 bg-slate-50/80 dark:bg-gray-800/80 overflow-hidden"
            :class="$store.sidebar.collapsed ? 'flex-col gap-2 px-2 py-3 justify-center' : 'flex-row gap-3 px-4 py-5'">

            {{-- Logo --}}
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 rounded-xl bg-white dark:bg-gray-700 shadow-sm ring-1 ring-slate-200 dark:ring-gray-600">
                <img src="{{ asset('img/logo-paduka-fill.svg') }}" alt="Logo" class="w-7 h-7 object-contain">
            </div>

            {{-- Nama sistem — hanya saat expanded --}}
            <div class="flex-1 min-w-0 overflow-hidden transition-all duration-200"
                :class="$store.sidebar.collapsed ? 'opacity-0 w-0 h-0' : 'opacity-100'">
                <span class="block text-base font-semibold tracking-tight text-slate-800 dark:text-gray-100 truncate whitespace-nowrap">
                    {{ config('app.name', 'Laravel') }}
                </span>
                <p class="text-sm text-slate-500 dark:text-gray-400 leading-none whitespace-nowrap">Website</p>
            </div>

            {{-- Toggle Button — SELALU VISIBLE --}}
            <button @click="$store.sidebar.toggle()"
                title="Toggle Sidebar"
                class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-200 dark:hover:bg-gray-600 hover:text-slate-700 dark:hover:text-gray-200 transition-all duration-200">
                <svg class="w-4 h-4 transition-transform duration-300"
                    :class="$store.sidebar.collapsed ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto overflow-x-hidden">

            {{-- Semua role bisa akses --}}
            <div class="space-y-1">

                {{-- Section label --}}
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-gray-500 transition-all duration-200 overflow-hidden whitespace-nowrap"
                    :class="$store.sidebar.collapsed ? 'opacity-0 h-0 py-0 mb-0' : 'opacity-100'">
                    Menu Utama
                </p>

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                    :title="$store.sidebar.collapsed ? 'Dashboard' : ''"
                    class="group flex items-center gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                    {{ request()->routeIs('dashboard')
                        ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                        : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                            : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </span>
                    <span class="whitespace-nowrap overflow-hidden transition-all duration-200" :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">Dashboard</span>
                </a>

                {{-- NCR Dropdown --}}
                <div x-data="{ open: {{ request()->routeIs('ncr.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button type="button"
                        @click="$store.sidebar.collapsed ? (window.location.href = '{{ route('ncr.index') }}') : (open = !open)"
                        :title="$store.sidebar.collapsed ? 'NCR' : ''"
                        class="group w-full flex items-center justify-between gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('ncr.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                            : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                                {{ request()->routeIs('ncr.*')
                                    ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                    : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </span>
                            <span class="whitespace-nowrap overflow-hidden transition-all duration-200" :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">NCR</span>
                        </div>
                        <svg class="w-4 h-4 flex-shrink-0 transition-all duration-200"
                            :class="{ 'rotate-180': open, 'opacity-0 w-0': $store.sidebar.collapsed }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open && !$store.sidebar.collapsed" x-collapse class="ml-4 pl-4 border-l border-slate-200 dark:border-gray-600 space-y-1">
                        <a href="{{ route('ncr.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                            {{ request()->routeIs('ncr.*') && !request()->routeIs('ncr.verifikasi.*')
                                ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                {{ request()->routeIs('ncr.*') && !request()->routeIs('ncr.verifikasi.*') ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                            Registrasi NCR
                        </a>

                        <a href="{{ route('ncr.verifikasi.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                            {{ request()->routeIs('ncr.verifikasi.*')
                                ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                {{ request()->routeIs('ncr.verifikasi.*') ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                            Verifikasi NCR
                        </a>
                    </div>
                </div>
            </div>

            {{-- Admin & Superadmin --}}
            @if(in_array($level, ['admin', 'superadmin']))
                <div class="pt-2 space-y-1">
                    <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-gray-500 transition-all duration-200 overflow-hidden whitespace-nowrap"
                        :class="$store.sidebar.collapsed ? 'opacity-0 h-0 py-0 mb-0' : 'opacity-100'">
                        Manajemen
                    </p>

                    <a href="{{ route('temuan.index') }}"
                        :title="$store.sidebar.collapsed ? 'Daftar Lokasi Temuan' : ''"
                        class="group flex items-center gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('temuan.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                            : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                            {{ request()->routeIs('temuan.*')
                                ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-200" :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">Daftar Lokasi Temuan</span>
                    </a>

                    <a href="{{ route('projects.index') }}"
                        :title="$store.sidebar.collapsed ? 'Daftar Project' : ''"
                        class="group flex items-center gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('projects.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                            : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                            {{ request()->routeIs('projects.*')
                                ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-200" :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">Daftar Project</span>
                    </a>
                </div>
            @endif

            {{-- Superadmin saja --}}
            @if($level === 'superadmin')
                <div class="pt-2 space-y-1">
                    <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-gray-500 transition-all duration-200 overflow-hidden whitespace-nowrap"
                        :class="$store.sidebar.collapsed ? 'opacity-0 h-0 py-0 mb-0' : 'opacity-100'">
                        Administrasi
                    </p>

                    <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('unit-kerja.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button type="button"
                            @click="$store.sidebar.collapsed ? (window.location.href = '{{ route('users.index') }}') : (open = !open)"
                            :title="$store.sidebar.collapsed ? 'Users / Pengguna' : ''"
                            class="group w-full flex items-center justify-between gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                            {{ request()->routeIs('users.*') || request()->routeIs('unit-kerja.*')
                                ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                                : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                                    {{ request()->routeIs('users.*') || request()->routeIs('unit-kerja.*')
                                        ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                        : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <span class="whitespace-nowrap overflow-hidden transition-all duration-200" :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">Users / Pengguna</span>
                            </div>
                            <svg class="w-4 h-4 flex-shrink-0 transition-all duration-200"
                                :class="{ 'rotate-180': open, 'opacity-0 w-0': $store.sidebar.collapsed }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open && !$store.sidebar.collapsed" x-collapse class="ml-4 pl-4 border-l border-slate-200 dark:border-gray-600 space-y-1">
                            <a href="{{ route('unit-kerja.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                {{ request()->routeIs('unit-kerja.*')
                                    ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                    : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                    {{ request()->routeIs('unit-kerja.*') ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                                Management Unit Kerja
                            </a>

                            <a href="{{ route('users.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                {{ request()->routeIs('users.*')
                                    ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                    : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                    {{ request()->routeIs('users.*') ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                                Management Users
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </nav>

        {{-- Profile & Logout --}}
        <div class="border-t border-slate-200 dark:border-gray-700 p-2 flex items-center gap-2">

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}"
                :title="$store.sidebar.collapsed ? '{{ Auth::user()->name }}' : ''"
                class="flex-1 min-w-0 flex items-center gap-3 px-1.5 py-2 rounded-xl hover:bg-white dark:hover:bg-gray-700 transition-all duration-200 ring-1 ring-transparent hover:ring-slate-200 dark:hover:ring-gray-600">

                @if(Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->name }}"
                        class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-gray-600 flex-shrink-0 shadow-sm" />
                @else
                    <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <div class="overflow-hidden min-w-0 transition-all duration-200" :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                    <p class="text-sm font-semibold text-slate-800 dark:text-gray-100 truncate whitespace-nowrap">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400 truncate whitespace-nowrap">
                        {{ Auth::user()->jabatan ?? '-' }} - {{ Auth::user()->unit_kerja ?? '-' }}
                    </p>
                </div>
            </a>

            {{-- Logout Button dengan animated SVG icon --}}
            <button type="button"
                x-show="!$store.sidebar.collapsed"
                @click="openLogoutModal = true"
                title="Logout"
                class="logout-btn flex-shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-xl border border-red-100 dark:border-red-900/30 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 hover:border-red-200 dark:hover:border-red-800/40 transition-all duration-200">

                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    {{-- Pintu (hitam/merah sesuai tema) --}}
                    <path class="logout-door" stroke="currentColor"
                        d="M4 19.5C4 20.3 4.7 21 5.5 21H12V3H5.5C4.7 3 4 3.7 4 4.5V19.5Z"
                        style="color: rgb(220 38 38);" />
                    {{-- Panah teal animasi --}}
                    <g class="logout-arrow">
                        <line x1="9" y1="12" x2="19" y2="12" stroke="#2dd4bf" />
                        <polyline points="16 9 19 12 16 15" stroke="#2dd4bf" />
                    </g>
                </svg>

            </button>

            <style>
                /* Panah geser kanan saat hover */
                .logout-btn .logout-arrow {
                    transform: translateX(0);
                    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
                }
                .logout-btn:hover .logout-arrow {
                    transform: translateX(3px);
                }

                /* Pintu sedikit mundur ke kiri saat hover */
                .logout-btn .logout-door {
                    transform-origin: left center;
                    transition: transform 0.3s ease;
                }
                .logout-btn:hover .logout-door {
                    transform: scaleX(0.88);
                }
            </style>

        </div>

    </aside>

    {{-- Modal Logout --}}
    <div x-show="openLogoutModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        style="display: none;">

        <div @click.away="openLogoutModal = false"
            class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">

            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Konfirmasi Logout</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Apakah Anda yakin ingin keluar dari sistem?</p>

            <div class="flex justify-end gap-3 mt-6">
                <button @click="openLogoutModal = false"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
