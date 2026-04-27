@extends('layouts.app')

@section('header')
    Edit Changelog
@endsection

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">

        <div class="mb-6">
            <a href="{{ route('changelog.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-200 transition mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            <h1 class="text-xl font-bold text-slate-800 dark:text-gray-100">Edit Changelog</h1>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Perbarui informasi versi dan poin perubahan.</p>
        </div>

        <form method="POST" action="{{ route('changelog.update', $changelog) }}"
            x-data="{ items: {{ json_encode(old('items', $changelog->items->pluck('isi')->toArray())) }} }">
            @csrf
            @method('PUT')
            @include('changelog._form')
        </form>

    </div>
@endsection
