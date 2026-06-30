@php
    $level = strtolower(Auth::user()->level ?? '');

    $menuItemBase =
        'relative group flex items-center gap-3 rounded-2xl px-2 py-2 text-sm font-semibold transition-all duration-300';
    $menuItemIdle =
        'text-slate-700 hover:-translate-y-0.5 hover:bg-white/85 hover:text-slate-950 dark:text-slate-200 dark:hover:bg-white/10 dark:hover:text-white';
    $menuItemActive =
        'bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 text-white shadow-lg shadow-indigo-500/25';

    $iconBase = 'flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl transition-all duration-300';
    $iconIdle =
        'bg-white/85 text-slate-600 ring-1 ring-slate-200/80 shadow-sm group-hover:bg-white group-hover:text-indigo-600 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10 dark:group-hover:bg-white/15 dark:group-hover:text-white';
    $iconActive = 'bg-white/20 text-white ring-1 ring-white/25 shadow-sm';

    $childBase = 'group/child flex items-center gap-2 rounded-xl px-3 py-2 text-sm transition-all duration-200';
    $childIdle =
        'text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white';
    $childActive =
        'bg-indigo-50 text-indigo-700 font-semibold ring-1 ring-indigo-100 dark:bg-indigo-500/15 dark:text-indigo-200 dark:ring-indigo-400/20';

    $sectionTitle =
        'px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500 transition-all duration-200 overflow-hidden whitespace-nowrap';
@endphp

<div x-data="{ openLogoutModal: false }">

    {{-- Toggle Button --}}
    <button type="button" @click="$store.sidebar.toggle()" title="Toggle Sidebar"
        :style="$store.sidebar.collapsed ? 'left: 58px' : 'left: 244px'"
        class="fixed top-[50px] z-50 flex h-7 w-7 items-center justify-center rounded-full
            border border-slate-200/80 bg-white/90 text-slate-500 shadow-lg shadow-slate-900/10 backdrop-blur
            transition-all duration-300 hover:scale-105 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600
            dark:border-white/10 dark:bg-slate-900/90 dark:text-slate-300 dark:shadow-black/30
            dark:hover:border-indigo-400/40 dark:hover:bg-indigo-500/20 dark:hover:text-indigo-200">
        <svg class="h-3.5 w-3.5 transition-transform duration-300" :class="$store.sidebar.collapsed ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <aside :class="$store.sidebar.collapsed ? 'w-[72px]' : 'w-64'"
        class="fixed left-0 top-0 z-40 flex h-full flex-col overflow-hidden
            border-r border-slate-200/70 bg-white/90 shadow-xl shadow-slate-900/5 backdrop-blur-xl
            transition-all duration-300 ease-in-out
            dark:border-white/10 dark:bg-slate-950/95 dark:shadow-black/30">

        {{-- Decorative Background - Full Rail Pattern --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">

            {{-- Base soft glow --}}
            <div
                class="absolute -left-24 -top-24 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl dark:bg-indigo-500/20">
            </div>
            <div class="absolute -right-28 top-1/3 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl dark:bg-cyan-500/15">
            </div>
            <div
                class="absolute -bottom-28 left-8 h-72 w-72 rounded-full bg-fuchsia-500/10 blur-3xl dark:bg-fuchsia-500/15">
            </div>

            {{-- Subtle route lines across sidebar --}}
            <div class="absolute inset-y-0 left-0 right-0 opacity-[0.30] dark:opacity-[0.46]">
                <div
                    class="absolute left-5 top-20 h-[85%] w-px rotate-[8deg] bg-gradient-to-b from-transparent via-slate-600 to-transparent dark:via-white">
                </div>
                <div
                    class="absolute right-7 top-10 h-[90%] w-px -rotate-[10deg] bg-gradient-to-b from-transparent via-indigo-600 to-transparent dark:via-indigo-300">
                </div>
                <div
                    class="absolute left-1/2 top-0 h-full w-px -translate-x-1/2 rotate-[18deg] bg-gradient-to-b from-transparent via-fuchsia-600 to-transparent dark:via-fuchsia-300">
                </div>
            </div>

            {{-- Top rail section --}}
            <div class="absolute left-3 right-3 top-28 opacity-[0.34] dark:opacity-[0.50]">
                <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-700 to-transparent dark:via-white">
                </div>
                <div
                    class="mt-3 ml-6 h-px w-4/5 bg-gradient-to-r from-transparent via-indigo-600 to-transparent dark:via-indigo-300">
                </div>

                <div class="absolute left-8 top-[-12px] h-11 w-px rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div class="absolute left-20 top-[-12px] h-11 w-px rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div class="absolute left-32 top-[-12px] h-11 w-px rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div class="absolute left-44 top-[-12px] h-11 w-px rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
            </div>

            {{-- Middle rail curve illusion --}}
            <div class="absolute left-0 right-0 top-[43%] opacity-[0.32] dark:opacity-[0.38]">
                <div
                    class="mx-auto h-px w-56 rotate-[-8deg] bg-gradient-to-r from-transparent via-slate-700 to-transparent dark:via-white">
                </div>
                <div
                    class="mx-auto mt-4 h-px w-48 rotate-[-8deg] bg-gradient-to-r from-transparent via-cyan-600 to-transparent dark:via-cyan-300">
                </div>

                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px -translate-x-24 rotate-[18deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px -translate-x-12 rotate-[18deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px translate-x-0 rotate-[18deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px translate-x-12 rotate-[18deg] bg-slate-400/60 dark:bg-white/30">
                </div>
            </div>

            {{-- Bottom rail section --}}
            <div class="absolute bottom-24 left-0 right-0 opacity-[0.36] dark:opacity-[0.42]">
                <div
                    class="mx-auto h-px w-56 bg-gradient-to-r from-transparent via-slate-700 to-transparent dark:via-white">
                </div>
                <div
                    class="mx-auto mt-3 h-px w-48 bg-gradient-to-r from-transparent via-indigo-600 to-transparent dark:via-indigo-300">
                </div>

                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px -translate-x-28 rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px -translate-x-16 rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px -translate-x-4 rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px translate-x-8 rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
                <div
                    class="absolute left-1/2 top-[-18px] h-16 w-px translate-x-20 rotate-[28deg] bg-slate-400/60 dark:bg-white/30">
                </div>
            </div>

            {{-- Small station dots --}}
            <div
                class="absolute left-7 top-36 h-2 w-2 rounded-full bg-indigo-500/30 shadow-sm shadow-indigo-500/40 dark:bg-indigo-300/30">
            </div>
            <div
                class="absolute right-8 top-[38%] h-2 w-2 rounded-full bg-cyan-500/30 shadow-sm shadow-cyan-500/40 dark:bg-cyan-300/30">
            </div>
            <div
                class="absolute left-10 bottom-40 h-2 w-2 rounded-full bg-fuchsia-500/30 shadow-sm shadow-fuchsia-500/40 dark:bg-fuchsia-300/30">
            </div>

            {{-- Top shine --}}
            <div
                class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-indigo-400/50 to-transparent">
            </div>
        </div>

        {{-- Train Illustration --}}
        <div class="pointer-events-none absolute inset-x-0 bottom-0 z-0 h-[320px] overflow-hidden transition-all duration-300"
            :class="$store.sidebar.collapsed ?
                'opacity-0 translate-y-6 scale-90' :
                'opacity-100 translate-y-0 scale-100'">

            {{-- Fade background di belakang gambar --}}
            <div
                class="absolute inset-x-0 bottom-0 z-0 h-full
            bg-gradient-to-t from-white/90 via-white/20 to-transparent
            dark:from-slate-950/90 dark:via-slate-950/20 dark:to-transparent">
            </div>

            <img src="{{ asset('img/sidebar/sidebar_train_red.svg') }}" alt="" aria-hidden="true"
                class="absolute bottom-8 left-1/2 z-10 w-[315px] max-w-none -translate-x-1/2
            opacity-100 transition-all duration-300
            drop-shadow-[0_18px_30px_rgba(15,23,42,0.18)]
            dark:opacity-95
            dark:drop-shadow-[0_18px_30px_rgba(0,0,0,0.45)]">
        </div>

        {{-- Logo / App Name --}}
        <div class="relative z-10 border-b border-slate-200/70 p-3 dark:border-white/10">
            <div
                class="flex items-center gap-3 rounded-3xl bg-slate-50/90 p-2.5 ring-1 ring-slate-200/80
                    dark:bg-white/[0.06] dark:ring-white/10">

                <div
                    class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl
                        bg-white shadow-sm ring-1 ring-slate-200/80
                        dark:bg-slate-900 dark:ring-white/10">
                    <img src="{{ asset('img/logo-paduka-fill.svg') }}" alt="Logo" class="h-7 w-7 object-contain">
                </div>

                <div class="min-w-0 flex-1 overflow-hidden transition-all duration-300"
                    :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                    <span
                        class="block truncate whitespace-nowrap text-base font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ config('app.name', 'Laravel') }}
                    </span>

                    <div class="mt-1 flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/60"></span>
                        <p
                            class="truncate whitespace-nowrap text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Quality Portal
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="sidebar-scrollbar relative z-10 flex-1 overflow-y-auto overflow-x-hidden px-2.5 py-4 pb-56">

            {{-- Reading card mengikuti tinggi menu --}}
            <div
                class="relative z-10 space-y-3 rounded-[2rem]
            bg-white/62 p-2 backdrop-blur-[2px]
            ring-1 ring-white/50
            dark:bg-slate-950/45 dark:ring-white/10">

                {{-- Semua role bisa akses --}}
                <div class="space-y-1">
                    <p class="{{ $sectionTitle }}"
                        :class="$store.sidebar.collapsed ? 'h-0 py-0 opacity-0' : 'opacity-100'">
                        Menu Utama
                    </p>

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}" :title="$store.sidebar.collapsed ? 'Dashboard' : ''"
                        class="{{ $menuItemBase }} {{ request()->routeIs('dashboard') ? $menuItemActive : $menuItemIdle }}">

                        @if (request()->routeIs('dashboard'))
                            <span
                                class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                        @endif

                        <span
                            class="{{ $iconBase }} {{ request()->routeIs('dashboard') ? $iconActive : $iconIdle }}">
                            <i class="fa-solid fa-house-chimney text-[15px]"></i>
                        </span>

                        <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                            :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                            Dashboard
                        </span>
                    </a>

                    {{-- NCR Dropdown --}}
                    @php
                        $isNcrParentActive = request()->routeIs('ncr.*');

                        $isNcrRegistrasiActive =
                            request()->routeIs('ncr.*') &&
                            !request()->routeIs('ncr.verifikasi.*') &&
                            !request()->routeIs('ncr.terlambat');

                        $isNcrVerifikasiActive = request()->routeIs('ncr.verifikasi.*');
                        $isNcrTerlambatActive = request()->routeIs('ncr.terlambat');
                    @endphp

                    <div x-data="{ open: {{ $isNcrParentActive ? 'true' : 'false' }} }" class="space-y-1">
                        <button type="button"
                            @click="$store.sidebar.collapsed ? (window.location.href = '{{ route('ncr.index') }}') : (open = !open)"
                            :title="$store.sidebar.collapsed ? 'NCR' : ''"
                            class="{{ $menuItemBase }} w-full justify-between {{ $isNcrParentActive ? $menuItemActive : $menuItemIdle }}">

                            @if ($isNcrParentActive)
                                <span
                                    class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                            @endif

                            <div class="flex min-w-0 items-center gap-3">
                                <span class="{{ $iconBase }} {{ $isNcrParentActive ? $iconActive : $iconIdle }}">
                                    <i class="fa-solid fa-triangle-exclamation text-[15px]"></i>
                                </span>

                                <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                    :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                    NCR
                                </span>
                            </div>

                            <svg class="h-4 w-4 flex-shrink-0 transition-all duration-200"
                                :class="{ 'rotate-180': open, 'opacity-0 w-0': $store.sidebar.collapsed }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open && !$store.sidebar.collapsed" x-transition
                            class="ml-5 space-y-1 border-l border-indigo-200/70 pl-4 dark:border-indigo-400/20">

                            <a href="{{ route('ncr.index') }}"
                                class="{{ $childBase }} {{ $isNcrRegistrasiActive ? $childActive : $childIdle }}">
                                <span
                                    class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isNcrRegistrasiActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                Registrasi NCR
                            </a>

                            <a href="{{ route('ncr.verifikasi.index') }}"
                                class="{{ $childBase }} {{ $isNcrVerifikasiActive ? $childActive : $childIdle }}">
                                <span
                                    class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isNcrVerifikasiActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                Verifikasi NCR
                            </a>

                            <a href="{{ route('ncr.terlambat') }}"
                                class="{{ $childBase }} {{ $isNcrTerlambatActive ? $childActive : $childIdle }}">
                                <span
                                    class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isNcrTerlambatActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                NCR Terlambat
                            </a>
                        </div>
                    </div>

                    @php
                        $allowedUnits = [30, 31, 1, 9];
                        $canAccessFeedback =
                            auth()->check() &&
                            auth()->user()->unitKerja()->whereIn('unit_kerja.id', $allowedUnits)->exists();

                        $isFeedbackActive = request()->routeIs('feedback.index', 'feedback.show', 'feedback.pdf');
                    @endphp

                    @if ($canAccessFeedback)
                        <a href="{{ route('feedback.index') }}"
                            :title="$store.sidebar.collapsed ? 'Kepuasan Pelanggan' : ''"
                            class="{{ $menuItemBase }} {{ $isFeedbackActive ? $menuItemActive : $menuItemIdle }}">

                            @if ($isFeedbackActive)
                                <span
                                    class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                            @endif

                            <span class="{{ $iconBase }} {{ $isFeedbackActive ? $iconActive : $iconIdle }}">
                                <i class="fa-solid fa-star text-[15px]"></i>
                            </span>

                            <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                Kepuasan Pelanggan
                            </span>
                        </a>
                    @endif

                    @php
                        $allowedUnitsVisualCheck = [30, 31, 1];
                        $canAccessVisualCheck =
                            auth()->check() &&
                            auth()->user()->unitKerja()->whereIn('unit_kerja.id', $allowedUnitsVisualCheck)->exists();

                        $isVisualCheckActive = request()->routeIs('visual-check.*');
                    @endphp

                    @if ($canAccessVisualCheck)
                        <a href="{{ route('visual-check.index') }}"
                            :title="$store.sidebar.collapsed ? 'Visual Check' : ''"
                            class="{{ $menuItemBase }} {{ $isVisualCheckActive ? $menuItemActive : $menuItemIdle }}">

                            @if ($isVisualCheckActive)
                                <span
                                    class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                            @endif

                            <span class="{{ $iconBase }} {{ $isVisualCheckActive ? $iconActive : $iconIdle }}">
                                <i class="fa-solid fa-video text-[15px]"></i>
                            </span>

                            <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                VISIQ
                            </span>
                        </a>
                    @endif

                    @php
                        $allowedUnitsDurability = [30, 31, 1, 9];
                        $canAccessDurability =
                            auth()->check() &&
                            auth()->user()->unitKerja()->whereIn('unit_kerja.id', $allowedUnitsDurability)->exists();
                    @endphp

                    @if ($canAccessDurability)
                        @php
                            $isDurabilityParentActive = request()->routeIs('durability.*');
                            $isDurabilityResumeActive = request()->routeIs('durability.index');
                            $isDurabilityPenggantianKomponenActive = request()->routeIs(
                                'durability.penggantian-komponen',
                            );
                            $isDurabilityKomponenActive = request()->routeIs('durability.durability-komponen');
                            $isDurabilityLokasiActive = request()->routeIs('durability.lokasi');
                        @endphp

                        <div x-data="{ open: {{ $isDurabilityParentActive ? 'true' : 'false' }} }" class="space-y-1">
                            <button type="button"
                                @click="$store.sidebar.collapsed ? (window.location.href = '{{ route('durability.index') }}') : (open = !open)"
                                @dblclick="window.location.href = '{{ route('durability.index') }}'"
                                :title="$store.sidebar.collapsed ? 'Durability Produk' : ''"
                                class="{{ $menuItemBase }} w-full justify-between {{ $isDurabilityParentActive ? $menuItemActive : $menuItemIdle }}">

                                @if ($isDurabilityParentActive)
                                    <span
                                        class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                                @endif

                                <div class="flex min-w-0 items-center gap-3">
                                    <span
                                        class="{{ $iconBase }} {{ $isDurabilityParentActive ? $iconActive : $iconIdle }}">
                                        <i class="fa-solid fa-shield-halved text-[15px]"></i>
                                    </span>

                                    <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                        :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                        Durability Product
                                    </span>
                                </div>

                                <svg class="h-4 w-4 flex-shrink-0 transition-all duration-200"
                                    :class="{ 'rotate-180': open, 'opacity-0 w-0': $store.sidebar.collapsed }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open && !$store.sidebar.collapsed" x-transition
                                class="ml-5 space-y-1 border-l border-indigo-200/70 pl-4 dark:border-indigo-400/20">

                                <a href="{{ route('durability.index') }}"
                                    class="{{ $childBase }} {{ $isDurabilityResumeActive ? $childActive : $childIdle }}">
                                    <span
                                        class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isDurabilityResumeActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                    Resume
                                </a>

                                <a href="{{ route('durability.penggantian-komponen') }}"
                                    class="{{ $childBase }} {{ $isDurabilityPenggantianKomponenActive ? $childActive : $childIdle }}">
                                    <span
                                        class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isDurabilityPenggantianKomponenActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                    Penggantian Komponen
                                </a>

                                <a href="{{ route('durability.durability-komponen') }}"
                                    class="{{ $childBase }} {{ $isDurabilityKomponenActive ? $childActive : $childIdle }}">
                                    <span
                                        class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isDurabilityKomponenActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                    Ketahanan Komponen
                                </a>

                                <a href="{{ route('durability.lokasi') }}"
                                    class="{{ $childBase }} {{ $isDurabilityLokasiActive ? $childActive : $childIdle }}">
                                    <span
                                        class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isDurabilityLokasiActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                    Lokasi
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Admin & Superadmin --}}
                @if (in_array($level, ['admin', 'superadmin']))
                    <div class="space-y-1">
                        <p class="{{ $sectionTitle }}"
                            :class="$store.sidebar.collapsed ? 'h-0 py-0 opacity-0' : 'opacity-100'">
                            Manajemen
                        </p>

                        @php $isTemuanActive = request()->routeIs('temuan.*'); @endphp

                        <a href="{{ route('temuan.index') }}"
                            :title="$store.sidebar.collapsed ? 'Daftar Lokasi Temuan' : ''"
                            class="{{ $menuItemBase }} {{ $isTemuanActive ? $menuItemActive : $menuItemIdle }}">

                            @if ($isTemuanActive)
                                <span
                                    class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                            @endif

                            <span class="{{ $iconBase }} {{ $isTemuanActive ? $iconActive : $iconIdle }}">
                                <i class="fa-solid fa-location-dot text-[15px]"></i>
                            </span>

                            <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                Daftar Lokasi Temuan
                            </span>
                        </a>

                        @php $isProjectActive = request()->routeIs('projects.*'); @endphp

                        <a href="{{ route('projects.index') }}"
                            :title="$store.sidebar.collapsed ? 'Daftar Project' : ''"
                            class="{{ $menuItemBase }} {{ $isProjectActive ? $menuItemActive : $menuItemIdle }}">

                            @if ($isProjectActive)
                                <span
                                    class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                            @endif

                            <span class="{{ $iconBase }} {{ $isProjectActive ? $iconActive : $iconIdle }}">
                                <i class="fa-solid fa-folder-open text-[15px]"></i>
                            </span>

                            <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                Daftar Project
                            </span>
                        </a>
                    </div>
                @endif

                {{-- Bantuan & Info --}}
                <div class="space-y-1">
                    <p class="{{ $sectionTitle }}"
                        :class="$store.sidebar.collapsed ? 'h-0 py-0 opacity-0' : 'opacity-100'">
                        Informasi
                    </p>

                    @php $isBantuanActive = request()->routeIs('bantuan.*'); @endphp

                    <a href="{{ route('bantuan.index') }}" :title="$store.sidebar.collapsed ? 'Bantuan & Info' : ''"
                        class="{{ $menuItemBase }} {{ $isBantuanActive ? $menuItemActive : $menuItemIdle }}">

                        @if ($isBantuanActive)
                            <span
                                class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                        @endif

                        <span class="{{ $iconBase }} {{ $isBantuanActive ? $iconActive : $iconIdle }}">
                            <i class="fa-solid fa-circle-info text-[15px]"></i>
                        </span>

                        <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                            :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                            Bantuan & Info
                        </span>
                    </a>
                </div>

                {{-- Superadmin saja --}}
                @if ($level === 'superadmin')
                    <div class="space-y-1">
                        <p class="{{ $sectionTitle }}"
                            :class="$store.sidebar.collapsed ? 'h-0 py-0 opacity-0' : 'opacity-100'">
                            Administrasi
                        </p>

                        @php
                            $isUserParentActive = request()->routeIs('users.*') || request()->routeIs('unit-kerja.*');
                            $isUnitKerjaActive = request()->routeIs('unit-kerja.*');
                            $isUsersActive = request()->routeIs('users.*');
                        @endphp

                        <div x-data="{ open: {{ $isUserParentActive ? 'true' : 'false' }} }" class="space-y-1">
                            <button type="button"
                                @click="$store.sidebar.collapsed ? (window.location.href = '{{ route('users.index') }}') : (open = !open)"
                                :title="$store.sidebar.collapsed ? 'Users / Pengguna' : ''"
                                class="{{ $menuItemBase }} w-full justify-between {{ $isUserParentActive ? $menuItemActive : $menuItemIdle }}">

                                @if ($isUserParentActive)
                                    <span
                                        class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                                @endif

                                <div class="flex min-w-0 items-center gap-3">
                                    <span
                                        class="{{ $iconBase }} {{ $isUserParentActive ? $iconActive : $iconIdle }}">
                                        <i class="fa-solid fa-users-gear text-[15px]"></i>
                                    </span>

                                    <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                        :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                        Users / Pengguna
                                    </span>
                                </div>

                                <svg class="h-4 w-4 flex-shrink-0 transition-all duration-200"
                                    :class="{ 'rotate-180': open, 'opacity-0 w-0': $store.sidebar.collapsed }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open && !$store.sidebar.collapsed" x-transition
                                class="ml-5 space-y-1 border-l border-indigo-200/70 pl-4 dark:border-indigo-400/20">

                                <a href="{{ route('unit-kerja.index') }}"
                                    class="{{ $childBase }} {{ $isUnitKerjaActive ? $childActive : $childIdle }}">
                                    <span
                                        class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isUnitKerjaActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                    Management Unit Kerja
                                </a>

                                <a href="{{ route('users.index') }}"
                                    class="{{ $childBase }} {{ $isUsersActive ? $childActive : $childIdle }}">
                                    <span
                                        class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $isUsersActive ? 'bg-indigo-500 shadow-sm shadow-indigo-500/70' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                    Management Users
                                </a>
                            </div>
                        </div>

                        @php $isChangelogActive = request()->routeIs('changelog.*'); @endphp

                        <a href="{{ route('changelog.index') }}"
                            :title="$store.sidebar.collapsed ? 'Manajemen Changelog' : ''"
                            class="{{ $menuItemBase }} {{ $isChangelogActive ? $menuItemActive : $menuItemIdle }}">

                            @if ($isChangelogActive)
                                <span
                                    class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-white/80"></span>
                            @endif

                            <span class="{{ $iconBase }} {{ $isChangelogActive ? $iconActive : $iconIdle }}">
                                <i class="fa-solid fa-clipboard-list text-[15px]"></i>
                            </span>

                            <span class="overflow-hidden whitespace-nowrap transition-all duration-200"
                                :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                                Change Log
                            </span>
                        </a>
                    </div>
                @endif
            </div>
        </nav>

        {{-- Profile & Logout --}}
        {{-- <div class="relative border-t border-slate-200/70 p-3 dark:border-white/10">
            <div
                class="flex items-center gap-2 rounded-3xl bg-slate-50/90 p-2 ring-1 ring-slate-200/80
                    dark:bg-white/[0.06] dark:ring-white/10">

                <a
                    href="{{ route('profile.edit') }}"
                    :title="$store.sidebar.collapsed ? @js(Auth::user()->name) : ''"
                    class="group flex min-w-0 flex-1 items-center gap-2.5 rounded-2xl px-1.5 py-1.5 transition-all duration-200 hover:bg-white dark:hover:bg-white/10">

                    @if (Auth::user()->foto)
                        <img
                            src="{{ asset('storage/' . Auth::user()->foto) }}"
                            alt="{{ Auth::user()->name }}"
                            class="h-10 w-10 flex-shrink-0 rounded-2xl object-cover ring-2 ring-white shadow-sm dark:ring-white/20">
                    @else
                        <div
                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl
                                bg-gradient-to-br from-indigo-600 to-fuchsia-600 text-sm font-black text-white
                                shadow-lg shadow-indigo-500/20 ring-2 ring-white dark:ring-white/20">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif

                    <div
                        class="min-w-0 overflow-hidden transition-all duration-200"
                        :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">
                        <p class="truncate text-sm font-extrabold leading-tight text-slate-900 dark:text-white">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="mt-0.5 truncate text-[11px] font-medium text-slate-500 dark:text-slate-400">
                            {{ ucfirst($level ?: 'user') }}
                            <span class="text-slate-300 dark:text-slate-600">•</span>
                            {{ Auth::user()->unit_kerja ?? '-' }}
                        </p>
                    </div>
                </a>

                <button
                    type="button"
                    x-show="!$store.sidebar.collapsed"
                    @click="openLogoutModal = true"
                    title="Keluar"
                    class="logout-btn flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-2xl
                        text-slate-400 transition-all duration-200
                        hover:bg-red-50 hover:text-red-600
                        dark:text-slate-500 dark:hover:bg-red-500/15 dark:hover:text-red-300">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            class="logout-door"
                            stroke="currentColor"
                            d="M4 19.5C4 20.3 4.7 21 5.5 21H12V3H5.5C4.7 3 4 3.7 4 4.5V19.5Z" />
                        <g class="logout-arrow">
                            <line x1="9" y1="12" x2="19" y2="12" stroke="currentColor" />
                            <polyline points="16 9 19 12 16 15" stroke="currentColor" />
                        </g>
                    </svg>
                </button>
            </div>
        </div> --}}
        {{-- Sidebar Footer --}}
        <div class="relative z-20 border-t border-slate-200/70 p-3 dark:border-white/10">

            <div
                class="overflow-hidden rounded-3xl bg-white/85 p-2.5 shadow-lg shadow-slate-900/5 ring-1 ring-slate-200/80 backdrop-blur-md
                    dark:bg-slate-950/75 dark:shadow-black/20 dark:ring-white/10">

                <div class="flex items-center gap-2.5" :class="$store.sidebar.collapsed ? 'justify-center' : ''">

                    {{-- Logo --}}
                    <div
                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80
                            dark:bg-slate-900 dark:ring-white/10">
                        <img src="{{ asset('img/logo-paduka-fill.svg') }}" alt="REKA INKA Group"
                            class="h-6 w-6 object-contain">
                    </div>

                    {{-- Text --}}
                    <div class="min-w-0 overflow-hidden transition-all duration-300"
                        :class="$store.sidebar.collapsed ? 'w-0 opacity-0' : 'opacity-100'">

                        <p class="truncate text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                            © {{ date('Y') }} REKA INKA Group
                        </p>
                        <p
                            class="mt-0.5 truncate text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                            All rights reserved
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <style>

            .sidebar-scrollbar::-webkit-scrollbar-thumb {
                background: transparent;
                border-radius: 999px;
            }

            .sidebar-scrollbar:hover::-webkit-scrollbar-thumb {
                background: rgba(148, 163, 184, 0.22);
            }

            .dark .sidebar-scrollbar:hover::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.13);
            }
            .sidebar-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: rgba(148, 163, 184, 0.28) transparent;
            }

            .sidebar-scrollbar::-webkit-scrollbar {
                width: 5px;
            }

            .sidebar-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .sidebar-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(148, 163, 184, 0.22);
                border-radius: 999px;
            }

            .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(99, 102, 241, 0.45);
            }

            .dark .sidebar-scrollbar {
                scrollbar-color: rgba(255, 255, 255, 0.16) transparent;
            }

            .dark .sidebar-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.13);
            }

            .dark .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(129, 140, 248, 0.45);
            }

            .logout-btn .logout-arrow {
                transform: translateX(0);
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .logout-btn:hover .logout-arrow {
                transform: translateX(3px);
            }

            .logout-btn .logout-door {
                transform-origin: left center;
                transition: transform 0.3s ease;
            }

            .logout-btn:hover .logout-door {
                transform: scaleX(0.88);
            }
        </style>
    </aside>

    {{-- Modal Logout --}}
    <div x-show="openLogoutModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm"
        style="display: none;">

        <div @click.away="openLogoutModal = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="scale-95 opacity-0 translate-y-2"
            x-transition:enter-end="scale-100 opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="scale-100 opacity-100 translate-y-0"
            x-transition:leave-end="scale-95 opacity-0 translate-y-2"
            class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl shadow-slate-950/20
                dark:border-white/10 dark:bg-slate-900 dark:shadow-black/40">

            <div class="flex items-start gap-4">
                <div
                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-300">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>

                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">
                        Konfirmasi Logout
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin keluar dari sistem?
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="openLogoutModal = false"
                    class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition
                        hover:bg-slate-100 hover:text-slate-900
                        dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white">
                    Batal
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-2xl bg-red-600 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition
                            hover:-translate-y-0.5 hover:bg-red-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
