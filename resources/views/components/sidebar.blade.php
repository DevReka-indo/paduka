@php $level = strtolower(Auth::user()->level ?? ''); @endphp

<aside class="fixed top-0 left-0 h-full w-64 bg-white border-r border-slate-200 flex flex-col z-40 shadow-sm">

    {{-- Logo / App Name --}}
    <div class="flex items-center gap-4 px-6 py-5 border-b border-slate-200 bg-slate-50/80">
        <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
            <img src="{{ asset('img/logo-paduka-fill.svg') }}" alt="Logo" class="w-7 h-7 object-contain">
        </div>
        <div class="min-w-0">
            <span class="block text-base font-semibold tracking-tight text-slate-800 truncate">
                {{ config('app.name', 'Laravel') }}
            </span>
            <p class="text-sm text-slate-500 leading-none mb-1">Website</p>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 px-4 py-5 space-y-2 overflow-y-auto">

        {{-- Semua role bisa akses --}}
        <div class="space-y-1">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                Menu Utama
            </p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                {{ request()->routeIs('dashboard')
                    ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-100'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg transition
                    {{ request()->routeIs('dashboard')
                        ? 'bg-indigo-100 text-indigo-700'
                        : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            {{-- NCR Dropdown --}}
            <div x-data="{ open: {{ request()->routeIs('ncr.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="group w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                    {{ request()->routeIs('ncr.*')
                        ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-100'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg transition
                            {{ request()->routeIs('ncr.*')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <span>NCR</span>
                    </div>

                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse class="ml-4 pl-4 border-l border-slate-200 space-y-1">
                    <a href="{{ route('ncr.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('ncr.*') && !request()->routeIs('ncr.verifikasi.*')
                            ? 'bg-slate-100 text-slate-900 font-medium'
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            {{ request()->routeIs('ncr.*') && !request()->routeIs('ncr.verifikasi.*') ? 'bg-indigo-500' : 'bg-slate-300' }}"></span>
                        Registrasi NCR
                    </a>

                    <a href="{{ route('ncr.verifikasi.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('ncr.verifikasi.*')
                            ? 'bg-slate-100 text-slate-900 font-medium'
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            {{ request()->routeIs('ncr.verifikasi.*') ? 'bg-indigo-500' : 'bg-slate-300' }}"></span>
                        Verifikasi NCR
                    </a>
                </div>
            </div>
        </div>

        {{-- Admin & Superadmin --}}
        @if(in_array($level, ['admin', 'superadmin']))
            <div class="pt-3 space-y-1">
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                    Manajemen
                </p>

                <a href="{{ route('temuan.index') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                    {{ request()->routeIs('temuan.*')
                        ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-100'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('temuan.*')
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <span>Daftar Lokasi Temuan</span>
                </a>

                <a href="{{ route('projects.index') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                    {{ request()->routeIs('projects.*')
                        ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-100'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('projects.*')
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </span>
                    <span>Daftar Project</span>
                </a>
            </div>
        @endif

        {{-- Superadmin saja --}}
        @if($level === 'superadmin')
            <div class="pt-3 space-y-1">
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                    Administrasi
                </p>

                <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('unit-kerja.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button type="button" @click="open = !open"
                        class="group w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('users.*') || request()->routeIs('unit-kerja.*')
                            ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-100'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg transition
                                {{ request()->routeIs('users.*') || request()->routeIs('unit-kerja.*')
                                    ? 'bg-indigo-100 text-indigo-700'
                                    : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <span>Users / Pengguna</span>
                        </div>

                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="ml-4 pl-4 border-l border-slate-200 space-y-1">
                        <a href="{{ route('unit-kerja.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                            {{ request()->routeIs('unit-kerja.*')
                                ? 'bg-slate-100 text-slate-900 font-medium'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ request()->routeIs('unit-kerja.*') ? 'bg-indigo-500' : 'bg-slate-300' }}"></span>
                            Management Unit Kerja
                        </a>

                        <a href="{{ route('users.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                            {{ request()->routeIs('users.*')
                                ? 'bg-slate-100 text-slate-900 font-medium'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ request()->routeIs('users.*') ? 'bg-indigo-500' : 'bg-slate-300' }}"></span>
                            Management Users
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </nav>

    {{-- User Info --}}
    <div class="px-4 py-4 border-t border-slate-200 bg-slate-50/70">
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-white transition-all duration-200 ring-1 ring-transparent hover:ring-slate-200">
            @if(Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->name }}"
                    class="w-10 h-10 rounded-full object-cover border border-slate-200 flex-shrink-0 shadow-sm" />
            @else
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 bg-indigo-100 text-indigo-700 border border-indigo-200">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif

            <div class="overflow-hidden min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500 truncate">{{ ucfirst($level) }}</p>
            </div>
        </a>
    </div>

</aside>
