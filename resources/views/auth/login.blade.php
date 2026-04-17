<x-guest-layout>
    <div class="w-full max-w-[420px]">

        {{-- Logo --}}
        <div class="anim-fade-in flex justify-center mb-8">
            <img src="{{ asset('img/logo-paduka.svg') }}" alt="{{ config('app.name') }}"
                 class="w-20 object-contain opacity-90">
        </div>

        {{-- Mobile branding --}}
        <div class="anim-fade-up lg:hidden text-center mb-7">
            <h1 class="font-['DM_Serif_Display'] text-[32px] text-[#0b1d3e]">
                PAD<span class="text-[#1e40af]">U</span>KA
            </h1>
            <p class="text-xs font-light text-slate-400 mt-1.5 leading-relaxed">
                Sistem NCR Online Terpusat · PT. Rekaindo Global Jasa
            </p>
        </div>

        {{-- Card --}}
        <div class="anim-fade-up delay-100 relative bg-white border border-slate-200 rounded-3xl px-8 py-9 shadow-[0_8px_40px_rgba(11,29,62,0.08),0_1px_3px_rgba(0,0,0,0.04)]">
            <div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-[#1e40af]/30 to-transparent"></div>

            <div class="anim-fade-up delay-150 mb-7">
                <h2 class="font-['DM_Serif_Display'] text-[24px] text-[#0b1d3e] font-normal">Masuk ke Sistem</h2>
                <p class="text-[13px] font-light text-slate-400 mt-1 leading-relaxed">
                    Gunakan email atau username untuk mengakses akun Anda
                </p>
            </div>

            @if ($errors->any())
            <div class="anim-fade-up mb-5 flex gap-3 border border-red-200 bg-red-50 rounded-2xl px-4 py-3.5">
                <i class="fa-solid fa-triangle-exclamation text-red-400 mt-0.5 text-sm shrink-0"></i>
                <div>
                    <strong class="block text-[12px] font-semibold text-red-600 mb-1">Terjadi kesalahan:</strong>
                    <ul class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-[12px] text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div class="anim-fade-up delay-200">
                    <label for="login" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">
                        Username atau NIP
                    </label>
                    <input id="login" type="text" name="login"
                           value="{{ old('login') }}"
                           required autofocus autocomplete="username"
                           placeholder="Username atau NIP"
                           class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                    <x-input-error :messages="$errors->get('login')" class="mt-1.5" />
                </div>

                <div class="anim-fade-up delay-250">
                    <label for="password" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">
                        Password
                    </label>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <div class="anim-fade-up delay-300 flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-[12.5px] text-slate-500 font-light select-none">
                        <input type="checkbox" name="remember" id="remember_me"
                               class="w-3.5 h-3.5 rounded accent-[#1e40af]">
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-[12.5px] text-[#1e40af]/70 hover:text-[#1e40af] transition-colors duration-150 font-medium">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="anim-fade-up delay-400 pt-1">
                    <button type="submit"
                            class="group relative w-full flex items-center justify-center gap-2 overflow-hidden bg-[#0b1d3e] hover:bg-[#0f2552] text-white font-semibold text-[13.5px] tracking-wide rounded-xl px-6 py-3.5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(11,29,62,0.3)] shadow-[0_2px_8px_rgba(11,29,62,0.2)] active:translate-y-0">
                        <span class="absolute inset-0 bg-gradient-to-br from-white/[0.06] to-transparent pointer-events-none"></span>
                        <span>Masuk</span>
                        <i class="fa-solid fa-arrow-right text-[11px] transition-transform duration-200 group-hover:translate-x-1"></i>
                    </button>
                </div>
            </form>

            <div class="my-6 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>

            <p class="text-center text-[11px] font-light text-slate-300 tracking-wide">
                © {{ date('Y') }} PT. Rekaindo Global Jasa · All rights reserved
            </p>
        </div>
    </div>
</x-guest-layout>
