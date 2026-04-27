@extends('layouts.app')

@section('header')
    Manajemen Changelog
@endsection

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-gray-100">Manajemen Changelog</h1>
                <p class="text-sm text-slate-500 dark:text-gray-400 mt-0.5">Kelola riwayat pembaruan sistem.</p>
            </div>
            <a href="{{ route('changelog.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Versi
            </a>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 text-sm px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-gray-700/50 border-b border-slate-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600 dark:text-gray-300">Versi</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600 dark:text-gray-300">Tipe</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600 dark:text-gray-300">Tanggal</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600 dark:text-gray-300">Status</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600 dark:text-gray-300">Poin</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                    @forelse($changelogs as $log)
                    @php
                        $badgeClass = match($log->tipe) {
                            'feature'     => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
                            'improvement' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                            'release'     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                            'fix'         => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            default       => 'bg-slate-100 text-slate-600',
                        };

                        $badgeLabel = match($log->tipe) {
                            'feature'     => 'Fitur Baru',
                            'improvement' => 'Peningkatan',
                            'release'     => 'Rilis',
                            'fix'         => 'Perbaikan',
                            default       => $log->tipe,
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-5 py-3.5 font-semibold text-slate-800 dark:text-gray-100">{{ $log->versi }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 dark:text-gray-400">{{ $log->tanggal_rilis->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            @if($log->is_published)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Published</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 dark:bg-gray-700 dark:text-gray-400">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 dark:text-gray-400">{{ $log->items->count() }} poin</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('changelog.edit', $log) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-gray-700 hover:text-indigo-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('changelog.destroy', $log) }}"
                                    onsubmit="return confirm('Hapus changelog {{ $log->versi }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400 dark:text-gray-500">
                            Belum ada changelog.
                            <a href="{{ route('changelog.create') }}" class="text-indigo-600 hover:underline">Tambah sekarang</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($changelogs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 dark:border-gray-700">
                {{ $changelogs->links() }}
            </div>
            @endif
        </div>

    </div>
@endsection
