@extends('layouts.app')

@section('header')
    Tambah Daily Check QC Final - Elektrik
@endsection

@section('content')
    <div
        class="mx-auto w-full max-w-[1700px] space-y-6"
    >
        <div
            class="flex flex-col gap-4
                sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <p
                    class="text-sm font-medium text-indigo-600
                        dark:text-indigo-400"
                >
                    Monitoring QC · QC Final - Elektrik
                </p>

                <h1
                    class="mt-1 text-2xl font-bold text-gray-900
                        dark:text-gray-100"
                >
                    Tambah Daily Check
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Input hasil Final Inspection Elektrik.
                </p>
            </div>

            <a
                href="{{ route(
                    'monitoring-qc.final-electrical.daily-check.index'
                ) }}"
                class="inline-flex items-center justify-center rounded-xl
                    border border-gray-300 bg-white px-4 py-2.5
                    text-sm font-medium text-gray-700 shadow-sm
                    transition hover:bg-gray-50
                    dark:border-gray-600 dark:bg-gray-800
                    dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div
                class="rounded-2xl border border-red-200 bg-red-50
                    px-5 py-4 text-sm text-red-700
                    dark:border-red-800/50 dark:bg-red-900/20
                    dark:text-red-300"
            >
                <p class="font-semibold">
                    Terdapat data yang belum sesuai.
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route(
                'monitoring-qc.final-electrical.daily-check.store'
            ) }}"
        >
            @csrf

            @include(
                'monitoring-qc.final-electrical.daily-checks.partials.form'
            )
        </form>
    </div>
@endsection
