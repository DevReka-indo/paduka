@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')
<div
    x-data="{ activeTab: 'executive' }"
    class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8"
>
    <div class="mx-auto max-w-[1600px] space-y-6">

        <x-dashboard.header />

        {{--
            ============================
            DASHBOARD TAB NAVIGATION
            ============================

            Jika nanti ingin menambahkan tab baru:
            1. Tambahkan button tab baru pada bagian <nav> di bawah.
               Contoh:
               <button type="button" @click="activeTab = 'project'">Project Summary</button>

            2. Tambahkan panel baru di bawah bagian TAB CONTENT.
               Contoh:
               <div x-show="activeTab === 'project'" class="space-y-6">
                   <x-dashboard.project-summary />
               </div>

            3. Buat file component baru di folder:
               resources/views/components/dashboard/

               Contoh:
               resources/views/components/dashboard/project-summary.blade.php

            4. Jika component baru butuh data dari controller,
               tambahkan data tersebut di DashboardController lalu kirim sebagai props.
        --}}
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

            {{-- Tabs Header --}}
            <div class="border-b border-gray-100 px-4 dark:border-gray-800 sm:px-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    <nav class="-mb-px flex gap-2 overflow-x-auto" aria-label="Dashboard Tabs">

                        <button
                            type="button"
                            @click="activeTab = 'executive'"
                            class="group inline-flex shrink-0 items-center gap-2 border-b-2 px-3 py-4 text-sm font-semibold transition"
                            :class="activeTab === 'executive'
                                ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300'
                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-gray-200'"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl transition"
                                :class="activeTab === 'executive'
                                    ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300'
                                    : 'bg-gray-50 text-gray-400 group-hover:text-gray-600 dark:bg-gray-800 dark:text-gray-500 dark:group-hover:text-gray-300'"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                            </span>
                            Executive Summary
                        </button>

                        <button
                            type="button"
                            @click="activeTab = 'ncr'; setTimeout(() => window.dispatchEvent(new Event('dashboard:ncr-tab-active')), 80)"
                            class="group inline-flex shrink-0 items-center gap-2 border-b-2 px-3 py-4 text-sm font-semibold transition"
                            :class="activeTab === 'ncr'
                                ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300'
                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-gray-200'"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl transition"
                                :class="activeTab === 'ncr'
                                    ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300'
                                    : 'bg-gray-50 text-gray-400 group-hover:text-gray-600 dark:bg-gray-800 dark:text-gray-500 dark:group-hover:text-gray-300'"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-6a2 2 0 012-2h8M9 17H5a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v4m-4 8h10a2 2 0 002-2v-5a2 2 0 00-2-2h-2" />
                                </svg>
                            </span>
                            NCR Management Summary
                        </button>

                        <button
                            type="button"
                            @click="activeTab = 'feedback'"
                            class="group inline-flex shrink-0 items-center gap-2 border-b-2 px-3 py-4 text-sm font-semibold transition"
                            :class="activeTab === 'feedback'
                                ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300'
                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-gray-200'"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl transition"
                                :class="activeTab === 'feedback'
                                    ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300'
                                    : 'bg-gray-50 text-gray-400 group-hover:text-gray-600 dark:bg-gray-800 dark:text-gray-500 dark:group-hover:text-gray-300'"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z" />
                                </svg>
                            </span>
                            Customer Feedback Summary
                        </button>

                    </nav>

                    {{-- Filter Periode Bulan - Tahun --}}
                    <form method="GET" class="flex flex-wrap items-center gap-2 pb-4 lg:pb-0">
                        <span class="text-xs font-bold uppercase tracking-[0.16em] text-gray-400 dark:text-gray-500">
                            Periode
                        </span>

                        <select name="from"
                                onchange="this.form.submit()"
                                class="rounded-xl border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            @foreach($availableMonths as $month)
                                <option value="{{ $month['value'] }}"
                                    {{ request('from', $defaultFrom) == $month['value'] ? 'selected' : '' }}>
                                    {{ $month['label'] }}
                                </option>
                            @endforeach
                        </select>

                        <span class="text-sm text-gray-400">s/d</span>

                        <select name="to"
                                onchange="this.form.submit()"
                                class="rounded-xl border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            @foreach($availableMonths as $month)
                                <option value="{{ $month['value'] }}"
                                    {{ request('to', $defaultTo) == $month['value'] ? 'selected' : '' }}>
                                    {{ $month['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                </div>
            </div>

            {{-- Tab Content --}}
            <div class="p-4 sm:p-6">

                {{--
                    EXECUTIVE SUMMARY
                    Isi: Stat Cards + AI Insight
                --}}
                <div
                    x-show="activeTab === 'executive'"
                    class="space-y-6"
                >
                    <x-dashboard.stat-cards
                        :is-admin="$isAdmin"
                        :total-ncr="$totalNcr"
                        :total-open="$totalOpen"
                        :total-proses="$totalProses"
                        :total-close="$totalClose"
                        :total-project="$totalProject"
                        :total-user="$totalUser"
                    />

                    <x-dashboard.ai-insight
                        :ai-insights="$aiInsights"
                        :periode-label="$periodeLabel"
                    />
                </div>

                {{--
                    NCR MANAGEMENT SUMMARY
                    Isi: KPI NCR, chart tren, proporsi status.
                    Filter periode sekarang juga tersedia di dashboard utama.
                    Jika chart tidak muncul, pastikan component chart-script sudah diperbarui.
                --}}
                <div
                    x-show="activeTab === 'ncr'"
                    class="space-y-6"
                >
                    <x-dashboard.ncr-management-summary
                        :available-months="$availableMonths"
                        :default-from="$defaultFrom"
                        :default-to="$defaultTo"
                        :from="$from"
                        :to="$to"
                        :is-admin="$isAdmin"
                        :periode-label="$periodeLabel"
                        :filtered-total-ncr="$filteredTotalNcr"
                        :filtered-total-open="$filteredTotalOpen"
                        :filtered-total-proses="$filteredTotalProses"
                        :filtered-total-close="$filteredTotalClose"
                        :feedback-percentage="$feedbackPercentage"
                    />
                </div>

                {{--
                    CUSTOMER FEEDBACK SUMMARY
                    Isi: Skor rata-rata, KPI feedback, total survey, range nilai.
                --}}
                <div
                    x-show="activeTab === 'feedback'"
                    class="space-y-6"
                >
                    <x-dashboard.customer-feedback-summary
                        :avg-feedback-score="$avgFeedbackScore"
                        :feedback-percentage="$feedbackPercentage"
                        :total-feedback="$totalFeedback"
                        :max-feedback-score="$maxFeedbackScore"
                        :min-feedback-score="$minFeedbackScore"
                    />
                </div>

            </div>
        </div>

    </div>
</div>

<x-dashboard.chart-script
    :bulan-label="$bulanLabel"
    :data-open="$dataOpen"
    :data-proses="$dataProses"
    :data-close="$dataClose"
    :filtered-total-open="$filteredTotalOpen"
    :filtered-total-proses="$filteredTotalProses"
    :filtered-total-close="$filteredTotalClose"
/>
@endsection
