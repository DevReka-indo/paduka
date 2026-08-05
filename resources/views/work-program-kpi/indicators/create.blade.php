@extends('layouts.app')

@section('header')
    Tambah Indikator
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-indigo-500/10 blur-3xl"></div>

            <div class="relative">
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                    Program Kerja & KPI
                </p>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Tambah Indikator
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Tambahkan indikator Program Kerja atau KPI baru.
                </p>
            </div>
        </div>

        <x-work-program-kpi.indicators.form
            :action="route('work-program-kpi.indicators.store')"
            method="POST"
            submit-label="Simpan Indikator"
            :selected-type="$selectedType"
        />

    </div>
</div>
@endsection
