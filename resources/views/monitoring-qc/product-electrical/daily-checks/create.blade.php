@extends('layouts.app')

@section('header')
    Tambah Daily Check QC Product Elektrik
@endsection

@section('content_width', 'w-full')

@section('content')
    <div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1500px] space-y-6">
            {{-- Header --}}
            <div
                class="relative overflow-hidden rounded-3xl border border-white/70
                    bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="absolute -right-20 -top-20 h-56 w-56
                        rounded-full bg-indigo-500/10 blur-3xl"
                ></div>

                <div
                    class="absolute -bottom-24 left-10 h-56 w-56
                        rounded-full bg-cyan-500/10 blur-3xl"
                ></div>

                <div
                    class="relative flex flex-col gap-5
                        sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p
                            class="text-xs font-extrabold uppercase tracking-[0.18em]
                                text-indigo-600 dark:text-indigo-400"
                        >
                            Monitoring QC · QC Product Elektrik
                        </p>

                        <h1
                            class="mt-2 text-2xl font-bold tracking-tight
                                text-gray-900 dark:text-white"
                        >
                            Tambah Daily Check
                        </h1>

                        <p
                            class="mt-1 max-w-3xl text-sm leading-6
                                text-gray-500 dark:text-gray-400"
                        >
                            Input data pemeriksaan harian yang sebelumnya
                            dicatat pada sheet Daily Check.
                        </p>
                    </div>

                    <a
                        href="{{ route(
                            'monitoring-qc.product-electrical.daily-check.index'
                        ) }}"
                        class="inline-flex items-center justify-center rounded-xl
                            border border-gray-200 bg-white px-4 py-2.5
                            text-sm font-semibold text-gray-700 shadow-sm transition
                            hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800
                            dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Validation Summary --}}
            @if ($errors->any())
                <div
                    class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4
                        text-sm text-red-700
                        dark:border-red-900/40 dark:bg-red-900/20
                        dark:text-red-300"
                >
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                        <div>
                            <p class="font-bold">
                                Data belum dapat disimpan
                            </p>

                            <p class="mt-1 text-xs leading-5">
                                Periksa kembali field yang ditandai dan lengkapi
                                data yang masih belum sesuai.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route(
                    'monitoring-qc.product-electrical.daily-check.store'
                ) }}"
            >
                @csrf

                @include(
                    'monitoring-qc.product-electrical.daily-checks.partials.form'
                )
            </form>
        </div>
    </div>
@endsection
