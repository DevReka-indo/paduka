<header class="bg-white border-b border-gray-200 h-16 flex items-center px-6 justify-between sticky top-0 z-30 shadow-sm">

    {{-- Page Title --}}
    <div class="text-gray-800 font-semibold text-base">
        @hasSection('header')
            @yield('header')
        @else
            {{ config('app.name', 'Laravel') }}
        @endif
    </div>

    {{-- Right Side --}}
    <div class="flex items-center gap-4">

    <div class="relative" x-data="{ open: false }">
        @php
            $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
            $notifications = auth()->check() ? auth()->user()->unreadNotifications->take(8) : collect();
        @endphp

        {{-- Notification Bell --}}
        <button
            type="button"
            @click="open = !open"
            class="relative text-gray-400 hover:text-gray-600 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>

            @if ($unreadCount > 0)
                <span class="absolute -top-1 -right-1 min-w-[18px] h-4 px-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-bold leading-none">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </button>

        {{-- Dropdown --}}
        <div
            x-show="open"
            @click.away="open = false"
            x-transition
            style="display: none;"
            class="absolute right-0 mt-3 w-96 bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden z-50"
        >
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                <span class="text-xs text-gray-400">{{ $unreadCount }} baru</span>
            </div>

            <div class="max-h-80 overflow-y-auto">
                @forelse ($notifications as $notification)
                    <a
                        href="{{ route('notifications.read', $notification->id) }}"
                        class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition"
                    >
                        <div class="flex items-start gap-3">
                            <span class="mt-1 w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>

                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->data['message'] ?? '-' }}
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-6 text-center text-sm text-gray-400">
                        Tidak ada notifikasi baru
                    </div>
                @endforelse
            </div>

            <div class="px-4 py-3 bg-gray-50">
                <a href="{{ route('notifications.index') }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    Lihat semua notifikasi
                </a>
            </div>
        </div>
    </div>

        <div class="w-px h-6 bg-gray-200"></div>

        {{-- User Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center gap-2.5 text-sm text-gray-700 hover:text-gray-900 transition-colors focus:outline-none">

                {{-- Foto atau inisial --}}
                @if(Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}"
                         alt="{{ Auth::user()->name }}"
                         class="w-8 h-8 rounded-full object-cover border border-gray-200" />
                @else
                    <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <div class="hidden sm:block text-left">
                    <p class="font-medium text-gray-800 leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 leading-tight">{{ Auth::user()->email }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Dropdown --}}
            <div x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a>

                <div class="border-t border-gray-100 my-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>
