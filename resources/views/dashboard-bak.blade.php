@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')
<style>
    .dashboard-shell {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
    }

    .dashboard-section {
        border-radius: 1rem;
        overflow: hidden;
    }

    .dashboard-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid;
    }

    .dashboard-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .dashboard-section-subtitle {
        font-size: 0.75rem;
        margin-top: .15rem;
    }

    .ai-insight-panel {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
    }

    .ai-insight-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at left, rgba(99, 102, 241, .14), transparent 32%),
            radial-gradient(circle at right, rgba(14, 165, 233, .10), transparent 28%);
        pointer-events: none;
    }

    .ai-bot-icon {
        width: 72px;
        height: 72px;
        border-radius: 1.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: white;
        font-size: 2rem;
        box-shadow: 0 18px 40px rgba(79, 70, 229, .28);
        position: relative;
    }

    .ai-bot-icon::after {
        content: "";
        position: absolute;
        inset: -6px;
        border-radius: 1.65rem;
        border: 1px solid rgba(99, 102, 241, .35);
        animation: aiPulse 2.4s ease-in-out infinite;
    }

    @keyframes aiPulse {
        0%, 100% {
            transform: scale(.96);
            opacity: .45;
        }
        50% {
            transform: scale(1.08);
            opacity: .85;
        }
    }

    .ai-insight-row {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        font-size: .82rem;
        line-height: 1.5;
    }

    .ai-dot {
        width: 1.15rem;
        height: 1.15rem;
        min-width: 1.15rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .65rem;
        font-weight: 800;
        margin-top: .08rem;
    }

    .ai-dot-success {
        background: rgba(34, 197, 94, .15);
        color: #16a34a;
    }

    .ai-dot-warning {
        background: rgba(245, 158, 11, .16);
        color: #d97706;
    }

    .ai-dot-danger {
        background: rgba(239, 68, 68, .16);
        color: #dc2626;
    }

    .ai-dot-info {
        background: rgba(59, 130, 246, .16);
        color: #2563eb;
    }

    .dark .ai-dot-success {
        color: #22c55e;
    }

    .dark .ai-dot-warning {
        color: #fbbf24;
    }

    .dark .ai-dot-danger {
        color: #f87171;
    }

    .dark .ai-dot-info {
        color: #60a5fa;
    }

    .mini-health-card {
        border-radius: .9rem;
        padding: 1rem;
    }

    .mini-progress {
        height: .45rem;
        border-radius: 999px;
        overflow: hidden;
        margin-top: .75rem;
    }

    .mini-progress span {
        display: block;
        height: 100%;
        border-radius: inherit;
    }
</style>

<div class="py-6 px-4 sm:px-6 lg:px-8 dashboard-shell space-y-6">

    {{-- Greeting --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            Selamat datang, {{ Auth::user()->name }}
        </h2>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 gap-4 {{ $isAdmin ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }}">

        {{-- Total NCR --}}
        <a href="{{ route('ncr.index') }}"
            class="{{ $isAdmin ? 'lg:col-span-1' : 'col-span-2 lg:col-span-1' }}
                   bg-gray-800 dark:bg-gray-700 dark:ring-1 dark:ring-gray-600
                   text-white rounded-2xl p-5 flex flex-col justify-between min-h-[110px]
                   transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg
                   focus:outline-none focus:ring-2 focus:ring-gray-400">
            <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold">{{ $totalNcr }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $isAdmin ? 'Total Semua NCR' : 'Total NCR' }}
                </p>
            </div>
        </a>

        {{-- Open --}}
        <a href="{{ route('ncr.index', ['status' => 'open']) }}"
            class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/40
                   rounded-2xl p-5 flex flex-col justify-between min-h-[110px]
                   transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg
                   focus:outline-none focus:ring-2 focus:ring-red-200">
            <div class="w-9 h-9 bg-red-100 dark:bg-red-800/40 rounded-xl flex items-center justify-center">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalOpen }}</p>
                <p class="text-xs text-red-400 dark:text-red-500 mt-0.5">Open</p>
            </div>
        </a>

        {{-- Process --}}
        <a href="{{ route('ncr.index', ['status' => 'process']) }}"
            class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/40
                   rounded-2xl p-5 flex flex-col justify-between min-h-[110px]
                   transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg
                   focus:outline-none focus:ring-2 focus:ring-amber-200">
            <div class="w-9 h-9 bg-amber-100 dark:bg-amber-800/40 rounded-xl flex items-center justify-center">
                <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $totalProses }}</p>
                <p class="text-xs text-amber-400 dark:text-amber-500 mt-0.5">Process</p>
            </div>
        </a>

        {{-- Close --}}
        <a href="{{ route('ncr.index', ['status' => 'close']) }}"
            class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/40
                   rounded-2xl p-5 flex flex-col justify-between min-h-[110px]
                   transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg
                   focus:outline-none focus:ring-2 focus:ring-green-200">
            <div class="w-9 h-9 bg-green-100 dark:bg-green-800/40 rounded-xl flex items-center justify-center">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalClose }}</p>
                <p class="text-xs text-green-400 dark:text-green-500 mt-0.5">Closed</p>
            </div>
        </a>

        {{-- Project & User --}}
        @if($isAdmin)
        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40
                    rounded-2xl p-5 flex flex-col justify-between min-h-[110px]">
            <div class="w-9 h-9 bg-indigo-100 dark:bg-indigo-800/40 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <div class="mt-3 flex items-end justify-between">
                <div>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalProject }}</p>
                    <p class="text-xs text-indigo-400 dark:text-indigo-500 mt-0.5">Project</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-indigo-400 dark:text-indigo-500">{{ $totalUser }}</p>
                    <p class="text-xs text-indigo-300 dark:text-indigo-600">User aktif</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- AI Insight --}}
    <div class="ai-insight-panel bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="relative p-5 lg:p-6 grid grid-cols-1 lg:grid-cols-[90px_1fr] gap-5 items-center">

            <div class="flex justify-center lg:justify-start">
                <div class="ai-bot-icon">
                    🤖
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between gap-4 flex-wrap mb-3">
                    <div>
                        <p class="text-xs font-extrabold tracking-[0.12em] uppercase text-indigo-600 dark:text-indigo-400">
                            AI Insight
                        </p>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 mt-1">
                            Analisis Otomatis NCR & Feedback Pelanggan
                        </h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                            Periode {{ $periodeLabel ?? 'aktif' }}
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold
                                 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Groq AI
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse($aiInsights ?? [] as $insight)
                        @php
                            $type = $insight['type'] ?? 'info';

                            $dotClass = match($type) {
                                'success' => 'ai-dot-success',
                                'warning' => 'ai-dot-warning',
                                'danger' => 'ai-dot-danger',
                                default => 'ai-dot-info',
                            };

                            $symbol = match($type) {
                                'success' => '✓',
                                'warning' => '!',
                                'danger' => '!',
                                default => 'i',
                            };
                        @endphp

                        <div class="ai-insight-row rounded-xl p-3 bg-gray-50/80 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700">
                            <span class="ai-dot {{ $dotClass }}">
                                {{ $symbol }}
                            </span>

                            <span class="text-gray-600 dark:text-gray-300">
                                {{ $insight['text'] }}
                            </span>
                        </div>
                    @empty
                        <div class="ai-insight-row rounded-xl p-3 bg-gray-50/80 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700">
                            <span class="ai-dot ai-dot-info">i</span>
                            <span class="text-gray-600 dark:text-gray-300">
                                Belum ada insight yang dapat ditampilkan untuk periode ini.
                            </span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- NCR MANAGEMENT SUMMARY --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="dashboard-section-header border-gray-100 dark:border-gray-700">
            <div>
                <h3 class="dashboard-section-title text-gray-700 dark:text-gray-200">
                    NCR MANAGEMENT SUMMARY
                </h3>
                <p class="dashboard-section-subtitle text-gray-400 dark:text-gray-500">
                    Tren, proporsi, dan indikator status NCR periode {{ $periodeLabel ?? 'aktif' }}
                </p>
            </div>

            <form method="GET" class="flex items-center gap-2 flex-wrap">
                <select name="from"
                    onchange="this.form.submit()"
                    class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                    @foreach($availableMonths as $month)
                        <option value="{{ $month['value'] }}"
                            {{ request('from', $defaultFrom) == $month['value'] ? 'selected' : '' }}>
                            {{ $month['label'] }}
                        </option>
                    @endforeach
                </select>

                <span class="text-gray-400 text-sm">→</span>

                <select name="to"
                    onchange="this.form.submit()"
                    class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                    @foreach($availableMonths as $month)
                        <option value="{{ $month['value'] }}"
                            {{ request('to', $defaultTo) == $month['value'] ? 'selected' : '' }}>
                            {{ $month['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="p-4">

            @php
                $closeRate = $filteredTotalNcr > 0 ? round(($filteredTotalClose / $filteredTotalNcr) * 100) : 0;
                $openRate = $filteredTotalNcr > 0 ? round(($filteredTotalOpen / $filteredTotalNcr) * 100) : 0;
                $feedbackKpi = isset($feedbackPercentage) && $feedbackPercentage ? round($feedbackPercentage) : 0;
                $feedbackBar = min($feedbackKpi, 100);
            @endphp

            {{-- Health Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="mini-health-card bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/40">
                    <p class="text-xs font-semibold text-green-600 dark:text-green-400">NCR Closure Rate</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $closeRate }}%</p>
                    <div class="mini-progress bg-green-100 dark:bg-green-900/40">
                        <span style="width:{{ $closeRate }}%;background:#22c55e;"></span>
                    </div>
                </div>

                <div class="mini-health-card bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/40">
                    <p class="text-xs font-semibold text-red-600 dark:text-red-400">Open Issue Rate</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $openRate }}%</p>
                    <div class="mini-progress bg-red-100 dark:bg-red-900/40">
                        <span style="width:{{ $openRate }}%;background:#ef4444;"></span>
                    </div>
                </div>

                <div class="mini-health-card bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/40">
                    <p class="text-xs font-semibold text-amber-600 dark:text-amber-400">Feedback KPI</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ $feedbackKpi }}%</p>
                    <div class="mini-progress bg-amber-100 dark:bg-amber-900/40">
                        <span style="width:{{ $feedbackBar }}%;background:#f59e0b;"></span>
                    </div>
                </div>
            </div>

            {{-- Chart + Donut --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Bar Chart --}}
                <div class="lg:col-span-2 bg-gray-50 dark:bg-gray-700/40 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Tren NCR per Bulan</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                Statistik NCR berdasarkan bulan
                            </p>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <span class="w-2.5 h-2.5 bg-red-400 rounded-sm inline-block"></span>Open
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="w-2.5 h-2.5 bg-amber-400 rounded-sm inline-block"></span>Process
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="w-2.5 h-2.5 bg-green-400 rounded-sm inline-block"></span>Close
                            </span>
                        </div>
                    </div>

                    <div class="relative h-56">
                        <canvas id="chartTren"></canvas>
                    </div>
                </div>

                {{-- Donut --}}
                <div class="bg-gray-50 dark:bg-gray-700/40 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 flex flex-col">
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Proporsi Status</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $from)->translatedFormat('F Y') }}
                            -
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $to)->translatedFormat('F Y') }}
                            {{ !$isAdmin ? ' — NCR Anda' : '' }}
                        </p>
                    </div>

                    <div class="flex-1 flex items-center justify-center">
                        <div class="relative w-44 h-44">
                            <canvas id="chartDonut"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $filteredTotalNcr }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Total</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        @php
                            $pctOpen   = $filteredTotalNcr > 0 ? round($filteredTotalOpen / $filteredTotalNcr * 100) : 0;
                            $pctProses = $filteredTotalNcr > 0 ? round($filteredTotalProses / $filteredTotalNcr * 100) : 0;
                            $pctClose  = $filteredTotalNcr > 0 ? round($filteredTotalClose / $filteredTotalNcr * 100) : 0;
                        @endphp

                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                <span class="w-2.5 h-2.5 bg-red-400 rounded-full"></span>Open
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">
                                {{ $filteredTotalOpen }} <span class="text-gray-400 dark:text-gray-500 font-normal">({{ $pctOpen }}%)</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                <span class="w-2.5 h-2.5 bg-amber-400 rounded-full"></span>Process
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">
                                {{ $filteredTotalProses }} <span class="text-gray-400 dark:text-gray-500 font-normal">({{ $pctProses }}%)</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                <span class="w-2.5 h-2.5 bg-green-400 rounded-full"></span>Close
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">
                                {{ $filteredTotalClose }} <span class="text-gray-400 dark:text-gray-500 font-normal">({{ $pctClose }}%)</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Customer Feedback Summary --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="dashboard-section-header border-gray-100 dark:border-gray-700">
            <div>
                <h3 class="dashboard-section-title text-gray-700 dark:text-gray-200">
                    Customer Feedback Summary
                </h3>
                <p class="dashboard-section-subtitle text-gray-400 dark:text-gray-500">
                    Ringkasan kepuasan pelanggan berdasarkan periode dashboard
                </p>
            </div>

            <a href="{{ route('feedback.index') }}"
               class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">
                Lihat feedback
            </a>
        </div>

        <div class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-2xl p-5 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/40">
                <p class="text-xs font-semibold text-amber-600 dark:text-amber-400">Rata-rata Skor</p>
                <div class="flex items-end gap-2 mt-3">
                    <p class="text-4xl font-bold text-amber-600 dark:text-amber-400">
                        {{ isset($avgFeedbackScore) && $avgFeedbackScore ? number_format($avgFeedbackScore, 2) : '—' }}
                    </p>
                    <span class="text-sm text-gray-400 dark:text-gray-500 mb-1">/ 4</span>
                </div>
            </div>

            <div class="rounded-2xl p-5 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/40">
                <p class="text-xs font-semibold text-green-600 dark:text-green-400">Presentase KPI</p>
                <p class="text-4xl font-bold text-green-600 dark:text-green-400 mt-3">
                    {{ isset($feedbackPercentage) && $feedbackPercentage ? number_format($feedbackPercentage, 2) . '%' : '—' }}
                </p>
            </div>

            <div class="rounded-2xl p-5 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/40">
                <p class="text-xs font-semibold text-blue-600 dark:text-blue-400">Total Survey</p>
                <p class="text-4xl font-bold text-blue-600 dark:text-blue-400 mt-3">
                    {{ number_format($totalFeedback ?? 0) }}
                </p>
            </div>

            <div class="rounded-2xl p-5 bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Range Nilai</p>

                <div class="grid grid-cols-2 gap-3 mt-4">
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Tertinggi</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ isset($maxFeedbackScore) && $maxFeedbackScore ? number_format($maxFeedbackScore, 2) : '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Terendah</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                            {{ isset($minFeedbackScore) && $minFeedbackScore ? number_format($minFeedbackScore, 2) : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let chartTren = null;
    let chartDonut = null;
    let rebuildTimer = null;

    function getChartTheme() {
        const isDark = document.documentElement.classList.contains('dark');

        return {
            isDark,
            gridColor: isDark
                ? 'rgba(107, 114, 128, 0.22)'
                : 'rgba(107, 114, 128, 0.10)',
            tickColor: isDark ? '#9ca3af' : '#6b7280',
            labelColor: isDark ? '#d1d5db' : '#374151',
            donutBorderColor: isDark ? '#1f2937' : '#ffffff',
            tooltipBg: isDark ? 'rgba(17, 24, 39, 0.92)' : 'rgba(255, 255, 255, 0.96)',
            tooltipTitle: isDark ? '#f9fafb' : '#111827',
            tooltipBody: isDark ? '#e5e7eb' : '#374151',
        };
    }

    function destroyCharts() {
        if (chartTren) {
            chartTren.destroy();
            chartTren = null;
        }

        if (chartDonut) {
            chartDonut.destroy();
            chartDonut = null;
        }
    }

    function createCharts(animated = true) {
        const theme = getChartTheme();

        destroyCharts();

        const trenCanvas = document.getElementById('chartTren');
        const donutCanvas = document.getElementById('chartDonut');

        if (!trenCanvas || !donutCanvas) return;

        const ctxTren = trenCanvas.getContext('2d');
        const ctxDonut = donutCanvas.getContext('2d');

        chartTren = new Chart(ctxTren, {
            type: 'bar',
            data: {
                labels: @json($bulanLabel),
                datasets: [
                    {
                        label: 'Open',
                        data: @json($dataOpen),
                        backgroundColor: 'rgba(248, 113, 113, 0.82)',
                        borderRadius: 6,
                        maxBarThickness: 22,
                    },
                    {
                        label: 'Process',
                        data: @json($dataProses),
                        backgroundColor: 'rgba(251, 191, 36, 0.82)',
                        borderRadius: 6,
                        maxBarThickness: 22,
                    },
                    {
                        label: 'Close',
                        data: @json($dataClose),
                        backgroundColor: 'rgba(74, 222, 128, 0.82)',
                        borderRadius: 6,
                        maxBarThickness: 22,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: animated ? {
                    duration: 450,
                    easing: 'easeOutCubic',
                } : false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                    },
                },
                scales: {
                    x: {
                        stacked: false,
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            font: { size: 11 },
                            color: theme.tickColor,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: theme.gridColor,
                            drawBorder: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            font: { size: 11 },
                            color: theme.tickColor,
                            precision: 0,
                        },
                    },
                },
            },
        });

        chartDonut = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'Process', 'Close'],
                datasets: [{
                    data: [{{ $filteredTotalOpen }}, {{ $filteredTotalProses }}, {{ $filteredTotalClose }}],
                    backgroundColor: [
                        'rgba(248, 113, 113, 0.88)',
                        'rgba(251, 191, 36, 0.88)',
                        'rgba(74, 222, 128, 0.88)',
                    ],
                    borderColor: theme.donutBorderColor,
                    borderWidth: 3,
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                animation: animated ? {
                    duration: 500,
                    easing: 'easeOutCubic',
                } : false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round(ctx.raw / total * 100) : 0;
                                return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                            }
                        }
                    },
                },
            },
        });
    }

    createCharts(true);

    const observer = new MutationObserver((mutations) => {
        const classChanged = mutations.some(
            mutation => mutation.type === 'attributes' && mutation.attributeName === 'class'
        );

        if (!classChanged) return;

        clearTimeout(rebuildTimer);
        rebuildTimer = setTimeout(() => {
            createCharts(true);
        }, 120);
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
</script>
@endsection
