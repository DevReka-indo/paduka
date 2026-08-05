@extends('layouts.app')

@section('header')
    Edit Fasilitas Quality Control
@endsection

@section('content_width', 'w-full')

@section('content')
    <div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1400px] space-y-6">

            {{-- Header --}}
            <div
                class="relative overflow-hidden rounded-3xl border border-white/70
                    bg-white p-6 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="absolute -right-20 -top-20 h-56 w-56
                        rounded-full bg-blue-500/10 blur-3xl"
                ></div>

                <div
                    class="absolute -bottom-24 left-10 h-56 w-56
                        rounded-full bg-cyan-500/10 blur-3xl"
                ></div>

                <div
                    class="relative flex flex-col gap-5
                        sm:flex-row sm:items-center sm:justify-between"
                >
                    <div class="min-w-0">
                        <p
                            class="text-xs font-extrabold uppercase tracking-[0.18em]
                                text-blue-600 dark:text-blue-400"
                        >
                            Fasilitas Quality Control
                        </p>

                        <h1
                            class="mt-2 truncate text-2xl font-bold tracking-tight
                                text-gray-900 dark:text-white"
                        >
                            Edit {{ $qcFacility->name }}
                        </h1>

                        <p
                            class="mt-1 max-w-2xl text-sm leading-6
                                text-gray-500 dark:text-gray-400"
                        >
                            Perbarui informasi alat, spesifikasi teknis,
                            kalibrasi, kondisi, atau foto fasilitas.
                        </p>
                    </div>

                    <a
                        href="{{ route('qc-facilities.show', $qcFacility) }}"
                        class="inline-flex items-center justify-center rounded-xl
                            border border-gray-200 bg-white px-4 py-2.5
                            text-sm font-semibold text-gray-700 shadow-sm transition
                            hover:bg-gray-50
                            dark:border-gray-700 dark:bg-gray-800
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
                                Perubahan belum dapat disimpan
                            </p>

                            <p class="mt-1 text-xs leading-5">
                                Periksa kembali field yang ditandai dan pastikan
                                seluruh data telah diisi dengan benar.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('qc-facilities.update', $qcFacility) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                @include('qc-facilities.partials.form')
            </form>
        </div>
    </div>
@endsection
