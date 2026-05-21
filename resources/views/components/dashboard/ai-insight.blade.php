@props([
    'aiInsights' => [],
    'periodeLabel' => null,
])

<div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700/60 dark:bg-slate-900">

    {{-- ── Subtle background accent ── --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_-10%_-20%,theme(colors.indigo.50),transparent)] dark:bg-[radial-gradient(ellipse_80%_50%_at_-10%_-20%,theme(colors.indigo.950/40%),transparent)]"></div>

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="relative flex flex-col gap-4 px-5 pb-4 pt-5 sm:flex-row sm:items-start sm:justify-between">

        {{-- Left: icon + titles --}}
        <div class="flex items-start gap-4">

            {{-- Icon block --}}
            <div class="relative shrink-0">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-500/30 dark:bg-indigo-500 dark:shadow-indigo-500/20">
                    <i class="fa-solid fa-robot text-base"></i>
                </div>
                {{-- Online dot --}}
                <span class="absolute -right-0.5 -top-0.5 flex h-3.5 w-3.5 items-center justify-center">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900"></span>
                </span>
            </div>

            {{-- Titles --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">
                    AI Insight
                </p>
                <h3 class="mt-0.5 text-[15px] font-semibold leading-snug text-slate-800 dark:text-slate-100">
                    Analisis Otomatis NCR &amp; Feedback Pelanggan
                </h3>
                <p class="mt-1 flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                    <i class="fa-regular fa-calendar text-[11px]"></i>
                    Periode {{ $periodeLabel ?? 'aktif' }}
                </p>
            </div>
        </div>

        {{-- Right: provider pill --}}
        <div class="shrink-0 self-start sm:self-auto">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                <i class="fa-solid fa-bolt text-amber-400 text-[10px]"></i>
                Groq AI
            </span>
        </div>
    </div>

    {{-- ── Separator ── --}}
    <div class="relative mx-5">
        <div class="h-px bg-slate-100 dark:bg-slate-700/60"></div>
    </div>

    {{-- ══════════════════════════════
         INSIGHT CARDS
    ══════════════════════════════ --}}
    <div class="relative grid grid-cols-1 gap-3 p-5 sm:grid-cols-2">

        @forelse($aiInsights ?? [] as $insight)
            @php
                $type = $insight['type'] ?? 'info';

                $config = match($type) {
                    'success' => [
                        'wrap'  => 'bg-emerald-50 border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-800/50',
                        'icon'  => 'fa-solid fa-circle-check',
                        'iclr'  => 'text-emerald-500 dark:text-emerald-400',
                        'ibg'   => 'bg-emerald-100 dark:bg-emerald-900/50',
                        'label' => 'text-emerald-700 dark:text-emerald-400',
                        'lbg'   => 'bg-emerald-100 dark:bg-emerald-900/60',
                        'text'  => 'text-emerald-800 dark:text-emerald-300',
                        'tag'   => 'Positif',
                        'bar'   => 'bg-emerald-400',
                    ],
                    'warning' => [
                        'wrap'  => 'bg-amber-50 border-amber-200 dark:bg-amber-950/40 dark:border-amber-800/50',
                        'icon'  => 'fa-solid fa-triangle-exclamation',
                        'iclr'  => 'text-amber-500 dark:text-amber-400',
                        'ibg'   => 'bg-amber-100 dark:bg-amber-900/50',
                        'label' => 'text-amber-700 dark:text-amber-400',
                        'lbg'   => 'bg-amber-100 dark:bg-amber-900/60',
                        'text'  => 'text-amber-800 dark:text-amber-300',
                        'tag'   => 'Perhatian',
                        'bar'   => 'bg-amber-400',
                    ],
                    'danger' => [
                        'wrap'  => 'bg-rose-50 border-rose-200 dark:bg-rose-950/40 dark:border-rose-800/50',
                        'icon'  => 'fa-solid fa-circle-exclamation',
                        'iclr'  => 'text-rose-500 dark:text-rose-400',
                        'ibg'   => 'bg-rose-100 dark:bg-rose-900/50',
                        'label' => 'text-rose-700 dark:text-rose-400',
                        'lbg'   => 'bg-rose-100 dark:bg-rose-900/60',
                        'text'  => 'text-rose-800 dark:text-rose-300',
                        'tag'   => 'Kritis',
                        'bar'   => 'bg-rose-400',
                    ],
                    default => [
                        'wrap'  => 'bg-blue-50 border-blue-200 dark:bg-blue-950/40 dark:border-blue-800/50',
                        'icon'  => 'fa-solid fa-circle-info',
                        'iclr'  => 'text-blue-500 dark:text-blue-400',
                        'ibg'   => 'bg-blue-100 dark:bg-blue-900/50',
                        'label' => 'text-blue-700 dark:text-blue-400',
                        'lbg'   => 'bg-blue-100 dark:bg-blue-900/60',
                        'text'  => 'text-blue-800 dark:text-blue-300',
                        'tag'   => 'Informasi',
                        'bar'   => 'bg-blue-400',
                    ],
                };
            @endphp

            <div class="group relative flex gap-3.5 overflow-hidden rounded-xl border p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $config['wrap'] }}">

                {{-- Accent bar on left edge --}}
                <div class="absolute bottom-0 left-0 top-0 w-[3px] {{ $config['bar'] }} rounded-l-xl"></div>

                {{-- Icon --}}
                <div class="shrink-0 pt-0.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $config['ibg'] }}">
                        <i class="{{ $config['icon'] }} {{ $config['iclr'] }} text-sm"></i>
                    </div>
                </div>

                {{-- Content --}}
                <div class="min-w-0 flex-1 pl-1">
                    <div class="mb-1.5 flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $config['label'] }} {{ $config['lbg'] }}">
                            {{ $config['tag'] }}
                        </span>
                    </div>
                    <p class="text-[13px] leading-relaxed {{ $config['text'] }}">
                        {{ $insight['text'] }}
                    </p>
                </div>
            </div>

        @empty
            <div class="col-span-full flex flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-slate-200 bg-slate-50 py-10 text-center dark:border-slate-700 dark:bg-slate-800/40">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                    <i class="fa-regular fa-brain text-slate-400 text-lg dark:text-slate-500"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada insight tersedia</p>
                    <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">Insight akan muncul setelah data periode ini diproses.</p>
                </div>
            </div>
        @endforelse

    </div>

    {{-- ── Footer bar ── --}}
    <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/60 px-5 py-2.5 dark:border-slate-700/60 dark:bg-slate-800/30">
        <p class="text-[11px] text-slate-400 dark:text-slate-500">
            <i class="fa-regular fa-clock mr-1"></i>
            Diperbarui otomatis setiap periode
        </p>
        <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500">
            {{ count($aiInsights ?? []) }} insight ditemukan
        </p>
    </div>

</div>
