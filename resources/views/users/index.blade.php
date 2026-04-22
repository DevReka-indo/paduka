@extends('layouts.app')

@section('header')
    Kelola Pengguna
@endsection

@section('content')
    <div class="py-6 max-w-7xl mx-auto space-y-4">

        {{-- Header Bar --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-400 dark:text-gray-500">Kelola seluruh akun pengguna aplikasi</p>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        </div>

        {{-- Alert --}}
        @if (session('pesan'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/40 text-green-700 dark:text-green-300 text-sm px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('pesan') }}
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 text-red-700 dark:text-red-300 text-sm px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                    </svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-400 hover:text-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Filter & Search --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-48">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">Cari</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama, username, atau email..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-500 focus:border-indigo-400 dark:focus:border-indigo-500" />
                    </div>
                </div>
                <div class="w-40">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">Level</label>
                    <select name="level"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-500 focus:border-indigo-400 dark:focus:border-indigo-500">
                        <option value="">Semua Level</option>
                        <option value="admin" @selected(request('level') == 'admin')>Admin</option>
                        <option value="manager" @selected(request('level') == 'manager')>Manager</option>
                        <option value="user" @selected(request('level') == 'user')>User</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Cari
                    </button>
                    @if (request('search') || request('level'))
                        <a href="{{ route('users.index') }}"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500 text-center">
                            <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-center">Pengguna</th>
                            <th class="px-4 py-3 text-left font-semibold text-center">Username</th>
                            <th class="px-4 py-3 text-left font-semibold text-center">Jabatan</th>
                            <th class="px-4 py-3 text-left font-semibold text-center">Unit Kerja</th>
                            <th class="px-4 py-3 text-left font-semibold text-center">Level</th>
                            <th class="px-4 py-3 text-left font-semibold text-center">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">

                                <td class="px-4 py-3 text-gray-400 dark:text-gray-500">{{ $users->firstItem() + $loop->index }}</td>

                                {{-- Avatar + Nama + Email --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}"
                                                class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0" />
                                        @else
                                            <div
                                                class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->username ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->jabatan ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    @if ($user->unitKerja->count())
                                        {{ $user->unitKerja->pluck('nama_unit')->join(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Level Badge --}}
                                <td class="px-4 py-3">
                                    @if ($user->level == 'admin')
                                        <span class="inline-flex items-center text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-300 px-2.5 py-1 rounded-full">Admin</span>
                                    @elseif($user->level == 'manager')
                                        <span class="inline-flex items-center text-xs font-semibold bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-300 px-2.5 py-1 rounded-full">Manager</span>
                                    @elseif($user->level == 'superadmin')
                                        <span class="inline-flex items-center text-xs font-semibold bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-300 px-2.5 py-1 rounded-full">Super Admin</span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2.5 py-1 rounded-full">User</span>
                                    @endif
                                </td>

                                {{-- Status Aktif --}}
                                <td class="px-4 py-3">
                                    @if ($user->keterangan)
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-300 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('users.show', $user) }}"
                                            class="inline-flex items-center gap-1 text-xs font-medium bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 px-2.5 py-1.5 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="inline-flex items-center gap-1 text-xs font-medium bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/40 px-2.5 py-1.5 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                class="inline" x-data=""
                                                x-on:submit.prevent="if(confirm('Hapus user {{ $user->name }}?')) $el.submit()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 text-xs font-medium bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/40 px-2.5 py-1.5 rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-300 dark:text-gray-600">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Belum ada data pengguna</p>
                                        <a href="{{ route('users.create') }}"
                                            class="text-xs text-indigo-500 hover:underline">Tambah pengguna pertama</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} data
                    </p>
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
