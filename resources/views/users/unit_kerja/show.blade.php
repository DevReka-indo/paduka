@extends('layouts.app')

@section('header')
    Detail Unit Kerja
@endsection

@section('content')
<div class="py-6 max-w-7xl mx-auto space-y-4">

    {{-- Back Link --}}
    <a href="{{ route('unit-kerja.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar unit kerja
    </a>

    {{-- Header Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100">Informasi Unit Kerja</h2>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Detail unit kerja dan daftar pengguna yang terhubung</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('unit-kerja.edit', $unitKerja) }}"
                    class="inline-flex items-center gap-2 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 text-amber-600 dark:text-amber-300 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>

                <form action="{{ route('unit-kerja.destroy', $unitKerja) }}" method="POST"
                    x-data="" x-on:submit.prevent="if(confirm('Hapus unit kerja {{ $unitKerja->nama_unit }}?')) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-300 text-sm font-medium px-4 py-2 rounded-lg transition-colors
                        {{ $unitKerja->users_count > 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                        @if($unitKerja->users_count > 0) disabled title="Masih digunakan oleh {{ $unitKerja->users_count }} pengguna" @endif>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Kode Unit</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    {{ $unitKerja->kode_unit ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Unit Kerja</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    {{ $unitKerja->nama_unit }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah Pengguna</p>
                <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                    {{ $unitKerja->users_count ?? $unitKerja->users->count() }} pengguna
                </p>
            </div>

            <div class="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-3">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</p>
                @if($unitKerja->keterangan)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-300 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Nonaktif
                    </span>
                @endif
            </div>

            <div class="md:col-span-2 xl:col-span-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                </div>
                <div class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">
                    {{ $unitKerja->deskripsi ?: '-' }}
                </div>
            </div>

        </div>
    </div>

    {{-- Daftar Pengguna --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Section Header --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Daftar Pengguna</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Pengguna yang terhubung ke unit kerja ini</p>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                {{ ($unitKerja->users_count ?? $unitKerja->users->count()) > 0
                    ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-300'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500' }}">
                {{ $unitKerja->users_count ?? $unitKerja->users->count() }} pengguna
            </span>
        </div>

        {{-- Users Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">
                        <th class="px-4 py-3 text-left w-14">No</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Username</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Jabatan</th>
                        <th class="px-4 py-3 text-left">Level</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($unitKerja->users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">

                            <td class="px-4 py-3 text-gray-400 dark:text-gray-500">{{ $loop->iteration }}</td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($user->foto)
                                        <img src="{{ Storage::url($user->foto) }}" alt="{{ $user->name }}"
                                            class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-xs font-semibold border border-indigo-200 dark:border-indigo-700 flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-gray-100">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->username ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->jabatan ?? '-' }}</td>

                            <td class="px-4 py-3">
                                @php
                                    $level = strtolower($user->level ?? '');
                                    $levelClasses = match($level) {
                                        'superadmin' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-300 ring-1 ring-red-200 dark:ring-red-800/40',
                                        'admin'      => 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-300 ring-1 ring-indigo-200 dark:ring-indigo-800/40',
                                        'manager'    => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-300 ring-1 ring-amber-200 dark:ring-amber-800/40',
                                        'user'       => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-300 ring-1 ring-emerald-200 dark:ring-emerald-800/40',
                                        default      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 ring-1 ring-gray-200 dark:ring-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $levelClasses }}">
                                    {{ ucfirst($user->level ?? '-') }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @if($user->keterangan)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-300 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Nonaktif
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-300 dark:text-gray-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Belum ada pengguna pada unit kerja ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
