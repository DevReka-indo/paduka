@extends('layouts.app')

@section('header')
    Detail Pengguna
@endsection

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-4">

    <a href="{{ route('users.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar pengguna
    </a>

    {{-- Profile Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}"
                        class="w-16 h-16 rounded-2xl object-cover border-2 border-gray-200 dark:border-gray-600" />
                @else
                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($user->level == 'admin')
                            <span class="text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-300 px-2.5 py-0.5 rounded-full">Admin</span>
                        @elseif($user->level == 'superadmin')
                            <span class="text-xs font-semibold bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-300 px-2.5 py-0.5 rounded-full">Super Admin</span>
                        @elseif($user->level == 'manager')
                            <span class="text-xs font-semibold bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-300 px-2.5 py-0.5 rounded-full">Manager</span>
                        @else
                            <span class="text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2.5 py-0.5 rounded-full">User</span>
                        @endif
                        @if($user->keterangan)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-300 px-2.5 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 px-2.5 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('users.edit', $user) }}"
                class="inline-flex items-center gap-2 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 text-amber-600 dark:text-amber-300 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </div>

        {{-- Info Grid --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">

            @php
                $fields = [
                    'Username'    => $user->username,
                    'No. Telepon' => $user->no_telp,
                    'Jabatan'     => $user->jabatan,
                    'Departemen'  => $user->departemen,
                    'Divisi'      => $user->divisi,
                ];
            @endphp

            @foreach($fields as $label => $value)
            <div>
                <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ $label }}</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">{{ $value ?: '-' }}</p>
            </div>
            @endforeach

            <div>
                <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Bergabung Sejak</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                    {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}
                </p>
            </div>

        </div>

        {{-- Unit Kerja Section --}}
        <div class="border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Unit Kerja</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $user->unitKerja->count() }} unit kerja terdaftar
                    </p>
                </div>
            </div>

            <div class="p-6">
                @if($user->unitKerja->isEmpty())
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">Belum ada unit kerja yang ditetapkan.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->unitKerja as $unit)
                            <div class="flex items-center gap-2 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40 px-3 py-2 rounded-xl">
                                <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">{{ $unit->nama_unit }}</p>
                                    @if($unit->kode_unit)
                                        <p class="font-mono text-xs text-indigo-400 dark:text-indigo-500">{{ $unit->kode_unit }}</p>
                                    @endif
                                </div>
                                @if(!$unit->keterangan)
                                    <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded ml-1">Nonaktif</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
