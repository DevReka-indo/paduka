@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Notifikasi</h1>
            <p class="text-sm text-gray-400 dark:text-gray-500">Notifikasi terbaru dan riwayat notifikasi Anda</p>
        </div>
    </div>

    {{-- Notifikasi Baru --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                Notifikasi Baru
                <span class="ml-2 inline-flex items-center justify-center min-w-[22px] h-5 px-1.5 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs font-bold">
                    {{ $unreadNotifications->count() }}
                </span>
            </h2>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($unreadNotifications as $notification)
                <a href="{{ route('notifications.read', $notification->id) }}"
                   class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-start gap-4">
                        <div class="mt-1">
                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full block"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                {{ $notification->data['message'] ?? '-' }}
                            </p>

                            @if(isset($notification->data['type']))
                                <x-notification-badge :type="$notification->data['type']" class="mt-2" />
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-sm text-gray-400 dark:text-gray-500 text-center">
                    Tidak ada notifikasi baru.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Riwayat Notifikasi --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Riwayat Notifikasi</h2>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($readNotifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}"
                   class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-start gap-4">
                        <div class="mt-1">
                            <span class="w-2.5 h-2.5 bg-gray-300 dark:bg-gray-600 rounded-full block"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $notification->data['message'] ?? '-' }}
                            </p>

                            <div class="mt-2 flex items-center gap-2 flex-wrap">
                                @if(isset($notification->data['type']))
                                    <x-notification-badge :type="$notification->data['type']" />
                                @endif

                                @if($notification->read_at)
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        Dibaca {{ $notification->read_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-sm text-gray-400 dark:text-gray-500 text-center">
                    Belum ada riwayat notifikasi.
                </div>
            @endforelse
        </div>

        @if($readNotifications->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $readNotifications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
