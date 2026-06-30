@php $level = strtolower(Auth::user()->level ?? ''); @endphp

<div x-data="{ openLogoutModal: false }">

    {{-- Toggle Button — fixed mengikuti lebar sidebar --}}
    <button @click="$store.sidebar.toggle()"
        title="Toggle Sidebar"
        :style="$store.sidebar.collapsed ? 'left: 60px' : 'left: 244px'"
        class="fixed top-[50px] z-50
            w-6 h-6 flex items-center justify-center rounded-full
            bg-white dark:bg-gray-700
            border border-slate-200 dark:border-gray-600
            text-slate-400 dark:text-gray-400
            hover:bg-indigo-50 dark:hover:bg-indigo-900/40
            hover:text-indigo-600 dark:hover:text-indigo-300
            hover:border-indigo-200 dark:hover:border-indigo-700
            shadow-sm transition-all duration-300">
        <svg class="w-3 h-3 transition-transform duration-300"
            :class="$store.sidebar.collapsed ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <aside
        :class="$store.sidebar.collapsed ? 'w-[72px]' : 'w-64'"
        class="fixed top-0 left-0 h-full bg-white dark:bg-gray-800 border-r border-slate-200 dark:border-gray-700 flex flex-col z-40 shadow-sm transition-all duration-300 ease-in-out overflow-hidden">

        {{-- Logo / App Name --}}
        <div class="relative flex items-center gap-3 px-3 py-4 border-b border-slate-200 dark:border-gray-700 bg-slate-50/80 dark:bg-gray-800/80">
            <div class="w-9 h-9 flex items-center justify-center flex-shrink-0 rounded-xl bg-white dark:bg-gray-700 shadow-sm ring-1 ring-slate-200 dark:ring-gray-600">
                <img src="{{ asset('img/logo-paduka-fill.svg') }}" alt="Logo" class="w-6 h-6 object-contain">
            </div>
            <div class="flex-1 min-w-0 overflow-hidden transition-all duration-300"
                :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                <span class="block text-base font-semibold tracking-tight text-slate-800 dark:text-gray-100 truncate whitespace-nowrap">
                    {{ config('app.name', 'Laravel') }}
                </span>
                <p class="text-xs text-slate-500 dark:text-gray-400 leading-none whitespace-nowrap">Website</p>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto overflow-x-hidden">

            {{-- Semua role bisa akses --}}
            <div class="space-y-1">

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
                @php
                    $isNcrParentActive = request()->routeIs('ncr.*');

                    $isNcrRegistrasiActive = request()->routeIs('ncr.*')
                        && !request()->routeIs('ncr.verifikasi.*')
                        && !request()->routeIs('ncr.terlambat');

                    $isNcrVerifikasiActive = request()->routeIs('ncr.verifikasi.*');
                    $isNcrTerlambatActive = request()->routeIs('ncr.terlambat');
                @endphp

                <div x-data="{ open: {{ $isNcrParentActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button type="button"
                        @click="$store.sidebar.collapsed ? (window.location.href = '{{ route('ncr.index') }}') : (open = !open)"
                        :title="$store.sidebar.collapsed ? 'NCR' : ''"
                        class="group w-full flex items-center justify-between gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                        {{ $isNcrParentActive
                            ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                            : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                                {{ $isNcrParentActive
                                    ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                    : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </span>

                            <span
                                class="whitespace-nowrap overflow-hidden transition-all duration-200"
                                :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                                NCR
                            </span>
                        </div>

                        <svg class="w-4 h-4 flex-shrink-0 transition-all duration-200"
                            :class="{ 'rotate-180': open, 'opacity-0 w-0': $store.sidebar.collapsed }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="open && !$store.sidebar.collapsed"
                        x-collapse
                        class="ml-4 pl-4 border-l border-slate-200 dark:border-gray-600 space-y-1">

                        <a href="{{ route('ncr.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                            {{ $isNcrRegistrasiActive
                                ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                {{ $isNcrRegistrasiActive ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                            Registrasi NCR
                        </a>

                        <a href="{{ route('ncr.verifikasi.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                            {{ $isNcrVerifikasiActive
                                ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                {{ $isNcrVerifikasiActive ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                            Verifikasi NCR
                        </a>

                        <a href="{{ route('ncr.terlambat') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                            {{ $isNcrTerlambatActive
                                ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                {{ $isNcrTerlambatActive ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                            NCR Terlambat
                        </a>
                    </div>
                </div>

                @php
                    $allowedUnits = [30, 31, 1, 9];
                    $canAccessFeedback = auth()->check()
                        && auth()->user()->unitKerja()
                            ->whereIn('unit_kerja.id', $allowedUnits)
                            ->exists();
                @endphp

                @if($canAccessFeedback)
                    {{-- ── Kepuasan Pelanggan ── --}}
                    <a href="{{ route('feedback.index') }}"
                        :title="$store.sidebar.collapsed ? 'Kepuasan Pelanggan' : ''"
                        class="group flex items-center gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('feedback.index', 'feedback.show', 'feedback.pdf')
                            ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                            : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">

                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                            {{ request()->routeIs('feedback.index', 'feedback.show', 'feedback.pdf')
                                ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">

                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </span>

                        <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                            :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                            Kepuasan Pelanggan
                        </span>
                    </a>
                @endif


                @php
                    $allowedUnitsVisualCheck = [30, 31, 1];
                    $canAccessVisualCheck = auth()->check()
                        && auth()->user()->unitKerja()
                            ->whereIn('unit_kerja.id', $allowedUnitsVisualCheck)
                            ->exists();
                @endphp

                {{-- Visual Check Menu --}}
                @if ($canAccessVisualCheck)
                <a href="{{ route('visual-check.index') }}"
                    :title="$store.sidebar.collapsed ? 'Visual Check' : ''"
                    class="group flex items-center gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                    {{ request()->routeIs('visual-check.*')
                        ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                        : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('visual-check.*')
                            ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                            : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                        :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                        VISIQ
                    </span>
                </a>
                @endif

                @php
                    $allowedUnitsDurability = [30, 31, 1, 9];
                    $canAccessDurability = auth()->check()
                        && auth()->user()->unitKerja()
                            ->whereIn('unit_kerja.id', $allowedUnitsDurability)
                            ->exists();
                @endphp

                @if ($canAccessDurability)
                    @php
                        $isDurabilityParentActive = request()->routeIs('durability.*');

                        $isDurabilityResumeActive = request()->routeIs('durability.index');

                        $isDurabilityPenggantianKomponenActive = request()->routeIs('durability.penggantian-komponen');

                        $isDurabilityKomponenActive = request()->routeIs('durability.durability-komponen');

                        $isDurabilityLokasiActive = request()->routeIs('durability.lokasi');
                    @endphp

                    {{-- Durability Produk Dropdown --}}
                    <div x-data="{ open: {{ $isDurabilityParentActive ? 'true' : 'false' }} }" class="space-y-1">
                        <button type="button"
                            @click="$store.sidebar.collapsed ? (window.location.href = '{{ route('durability.index') }}') : (open = !open)"
                            @dblclick="window.location.href = '{{ route('durability.index') }}'"
                            :title="$store.sidebar.collapsed ? 'Durability Produk' : ''"
                            class="group w-full flex items-center justify-between gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                            {{ $isDurabilityParentActive
                                ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                                : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">

                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                                    {{ $isDurabilityParentActive
                                        ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                        : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4M7.5 4.5h9A2.5 2.5 0 0119 7v10a2.5 2.5 0 01-2.5 2.5h-9A2.5 2.5 0 015 17V7a2.5 2.5 0 012.5-2.5z" />
                                    </svg>
                                </span>

                                <span
                                    class="whitespace-nowrap overflow-hidden transition-all duration-200"
                                    :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                                    Durability Product
                                </span>
                            </div>

                            <svg class="w-4 h-4 flex-shrink-0 transition-all duration-200"
                                :class="{ 'rotate-180': open, 'opacity-0 w-0': $store.sidebar.collapsed }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            x-show="open && !$store.sidebar.collapsed"
                            x-collapse
                            class="ml-4 pl-4 border-l border-slate-200 dark:border-gray-600 space-y-1">

                            <a href="{{ route('durability.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                {{ $isDurabilityResumeActive
                                    ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                    : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                    {{ $isDurabilityResumeActive ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                                Resume
                            </a>

                            <a href="{{ route('durability.penggantian-komponen') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                {{ $isDurabilityPenggantianKomponenActive
                                    ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                    : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                    {{ $isDurabilityPenggantianKomponenActive ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                                Penggantian Komponen
                            </a>

                            <a href="{{ route('durability.durability-komponen') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                {{ $isDurabilityKomponenActive
                                    ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                    : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                    {{ $isDurabilityKomponenActive ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                                Ketahanan Komponen
                            </a>

                            <a href="{{ route('durability.lokasi') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                                {{ $isDurabilityLokasiActive
                                    ? 'bg-slate-100 dark:bg-gray-700 text-slate-900 dark:text-gray-100 font-medium'
                                    : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                    {{ $isDurabilityLokasiActive ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-gray-600' }}"></span>
                                Lokasi
                            </a>
                        </div>
                    </div>
                @endif



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

            {{-- Bantuan & Info (semua user) --}}
            <div class="pt-2 space-y-1">
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-gray-500 transition-all duration-200 overflow-hidden whitespace-nowrap"
                    :class="$store.sidebar.collapsed ? 'opacity-0 h-0 py-0 mb-0' : 'opacity-100'">
                    Informasi
                </p>

                <a href="{{ route('bantuan.index') }}"
                    :title="$store.sidebar.collapsed ? 'Bantuan & Info' : ''"
                    class="group flex items-center gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                    {{ request()->routeIs('bantuan.*')
                        ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                        : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('bantuan.*')
                            ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                            : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                        :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                        Bantuan & Info
                    </span>
                </a>
            </div>

            {{-- Superadmin saja --}}
            @if($level === 'superadmin')
                <div class="pt-2 space-y-1">
                    <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-gray-500 transition-all duration-200 overflow-hidden whitespace-nowrap"
                        :class="$store.sidebar.collapsed ? 'opacity-0 h-0 py-0 mb-0' : 'opacity-100'">
                        Administrasi
                    </p>

                    {{-- Dropdown Users/Pengguna --}}
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

                    <a href="{{ route('changelog.index') }}"
                        :title="$store.sidebar.collapsed ? 'Manajemen Changelog' : ''"
                        class="group flex items-center gap-3 px-1.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('changelog.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm ring-1 ring-indigo-100 dark:ring-indigo-700'
                            : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-gray-100' }}">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg transition
                            {{ request()->routeIs('changelog.*')
                                ? 'bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300'
                                : 'bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 group-hover:text-slate-700 dark:group-hover:text-gray-200' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                            :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                            Change Log
                        </span>
                    </a>
                </div>
            @endif

        </nav>

        {{-- Profile & Logout --}}
        <div class="border-t border-slate-200 dark:border-gray-700/60 p-3">
            <div class="flex items-center gap-2">

                <a href="{{ route('profile.edit') }}"
                    :title="$store.sidebar.collapsed ? '{{ Auth::user()->name }}' : ''"
                    class="group flex-1 min-w-0 flex items-center gap-2.5 px-1.5 py-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-gray-700/60 transition-all duration-200">

                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->name }}"
                            class="w-9 h-9 rounded-full object-cover flex-shrink-0 ring-2 ring-slate-200 dark:ring-gray-600" />
                    @else
                        <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 ring-2 ring-indigo-200 dark:ring-indigo-700/50">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="overflow-hidden min-w-0 transition-all duration-200"
                        :class="$store.sidebar.collapsed ? 'opacity-0 w-0' : 'opacity-100'">
                        <p class="text-sm font-semibold text-slate-800 dark:text-gray-100 truncate leading-tight">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-slate-400 dark:text-gray-500 truncate leading-tight mt-0.5">
                            {{ Auth::user()->jabatan ?? Auth::user()->email }} - {{ Auth::user()->unit_kerja ?? '-' }}
                        </p>
                    </div>
                </a>

                <button type="button"
                    x-show="!$store.sidebar.collapsed"
                    @click="openLogoutModal = true"
                    title="Keluar"
                    class="logout-btn flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg
                        text-slate-400 dark:text-gray-500
                        hover:text-red-500 dark:hover:text-red-400
                        hover:bg-red-50 dark:hover:bg-red-900/20
                        transition-all duration-200">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path class="logout-door" stroke="currentColor"
                            d="M4 19.5C4 20.3 4.7 21 5.5 21H12V3H5.5C4.7 3 4 3.7 4 4.5V19.5Z" />
                        <g class="logout-arrow">
                            <line x1="9" y1="12" x2="19" y2="12" stroke="currentColor" />
                            <polyline points="16 9 19 12 16 15" stroke="currentColor" />
                        </g>
                    </svg>
                </button>

            </div>
        </div>

        <style>
            .logout-btn .logout-arrow {
                transform: translateX(0);
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .logout-btn:hover .logout-arrow { transform: translateX(3px); }
            .logout-btn .logout-door {
                transform-origin: left center;
                transition: transform 0.3s ease;
            }
            .logout-btn:hover .logout-door { transform: scaleX(0.88); }
        </style>

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
