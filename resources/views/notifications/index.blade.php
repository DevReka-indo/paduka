@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Notifikasi</h1>
            <p class="text-sm text-gray-400">Notifikasi terbaru dan riwayat notifikasi Anda</p>
        </div>
    </div>

    {{-- Notifikasi Baru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">
                Notifikasi Baru
                <span class="ml-2 inline-flex items-center justify-center min-w-[22px] h-5 px-1.5 rounded-full bg-red-50 text-red-600 text-xs font-bold">
                    {{ $unreadNotifications->count() }}
                </span>
            </h2>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($unreadNotifications as $notification)
                <a href="{{ route('notifications.read', $notification->id) }}"
                   class="block px-5 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-4">
                        <div class="mt-1">
                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full block"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification->data['message'] ?? '-' }}
                            </p>

                            @if(isset($notification->data['type']))
                                <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full
                                    {{ $notification->data['type'] === 'ncr_tanggapi' ? 'bg-amber-50 text-amber-600' : '' }}
                                    {{ $notification->data['type'] === 'ncr_verifikasi' ? 'bg-blue-50 text-blue-600' : '' }}
                                ">
                                    {{ str_replace('_', ' ', strtoupper($notification->data['type'])) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-sm text-gray-400 text-center">
                    Tidak ada notifikasi baru.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Riwayat Notifikasi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Riwayat Notifikasi</h2>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($readNotifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}"
                   class="block px-5 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-4">
                        <div class="mt-1">
                            <span class="w-2.5 h-2.5 bg-gray-300 rounded-full block"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-medium text-gray-700 truncate">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $notification->data['message'] ?? '-' }}
                            </p>

                            <div class="mt-2 flex items-center gap-2 flex-wrap">
                                @if(isset($notification->data['type']))
                                    <span class="inline-block text-xs px-2 py-1 rounded-full
                                        {{ $notification->data['type'] === 'ncr_tanggapi' ? 'bg-amber-50 text-amber-600' : '' }}
                                        {{ $notification->data['type'] === 'ncr_verifikasi' ? 'bg-blue-50 text-blue-600' : '' }}
                                    ">
                                        {{ str_replace('_', ' ', strtoupper($notification->data['type'])) }}
                                    </span>
                                @endif

                                @if($notification->read_at)
                                    <span class="text-xs text-gray-400">
                                        Dibaca {{ $notification->read_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-sm text-gray-400 text-center">
                    Belum ada riwayat notifikasi.
                </div>
            @endforelse
        </div>

        @if($readNotifications->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $readNotifications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
