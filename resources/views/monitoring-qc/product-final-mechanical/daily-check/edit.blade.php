@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Edit Daily Check Mekanik
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ $dailyCheck->final_assembly_product_name }}
                </p>
            </div>

            <a
                href="{{ route(
                    'monitoring-qc.product-final-mechanical.daily-check.show',
                    $dailyCheck
                ) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg
                    border border-slate-300 bg-white px-4 py-2 text-sm
                    font-medium text-slate-700 shadow-sm
                    hover:bg-slate-50
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-200 dark:hover:bg-slate-800"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
            dark:border-slate-800 dark:bg-slate-900"
        >
            <form
                method="POST"
                action="{{ route(
                    'monitoring-qc.product-final-mechanical.daily-check.update',
                    $dailyCheck
                ) }}"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                @include(
                    'monitoring-qc.product-final-mechanical.daily-check.partials.form'
                )

                <div class="flex justify-end gap-3 border-t border-slate-200 pt-6
                    dark:border-slate-800"
                >
                    <a
                        href="{{ route(
                            'monitoring-qc.product-final-mechanical.daily-check.show',
                            $dailyCheck
                        ) }}"
                        class="inline-flex items-center rounded-lg border
                            border-slate-300 px-4 py-2 text-sm font-medium
                            text-slate-700 hover:bg-slate-50
                            dark:border-slate-700 dark:text-slate-200
                            dark:hover:bg-slate-800"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg
                            bg-indigo-600 px-4 py-2 text-sm font-semibold
                            text-white shadow-sm hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
