<x-guest-layout>
    <div class="w-full max-w-[420px]">

        <div class="anim-fade-in flex justify-center mb-8">
            <img src="{{ asset('img/logo-paduka.svg') }}" alt="{{ config('app.name') }}"
                 class="w-20 object-contain opacity-90">
        </div>

        <div class="anim-fade-up delay-100 relative bg-white border border-slate-200 rounded-3xl px-8 py-9 shadow-[0_8px_40px_rgba(11,29,62,0.08),0_1px_3px_rgba(0,0,0,0.04)]">
            <div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-[#1e40af]/30 to-transparent"></div>

            {{-- Icon --}}
            <div class="anim-fade-up delay-150 flex justify-center mb-6">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shadow-sm">
                    <i class="fa-regular fa-envelope text-[#1e40af] text-xl"></i>
                </div>
            </div>

            <div class="anim-fade-up delay-200 mb-6 text-center">
                <h2 class="font-['DM_Serif_Display'] text-[24px] text-[#0b1d3e] font-normal">Lupa Password?</h2>
                <p class="text-[13px] font-light text-slate-400 mt-2 leading-relaxed max-w-[300px] mx-auto">
                    Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.
                </p>
            </div>

            @if (session('status'))
            <div class="anim-fade-up mb-5 flex gap-3 border border-emerald-200 bg-emerald-50 rounded-2xl px-4 py-3.5">
                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                <p class="text-[12.5px] text-emerald-700 font-light leading-relaxed">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div class="anim-fade-up delay-250">
                    <label for="email" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">
                        Alamat Email
                    </label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           placeholder="nama@perusahaan.com"
                           class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <div class="anim-fade-up delay-300 pt-1">
                    <button type="submit"
                            class="group relative w-full flex items-center justify-center gap-2 overflow-hidden bg-[#0b1d3e] hover:bg-[#0f2552] text-white font-semibold text-[13.5px] tracking-wide rounded-xl px-6 py-3.5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(11,29,62,0.3)] shadow-[0_2px_8px_rgba(11,29,62,0.2)] active:translate-y-0">
                        <span class="absolute inset-0 bg-gradient-to-br from-white/[0.06] to-transparent pointer-events-none"></span>
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Tautan Reset</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 text-[12.5px] text-slate-400 hover:text-[#1e40af] transition-colors duration-150">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke halaman login</span>
                </a>
            </div>
        </div>

        <p class="text-center text-[11px] font-light text-slate-300 tracking-wide mt-6">
            © {{ date('Y') }} PT. Rekaindo Global Jasa · All rights reserved
        </p>
    </div>
</x-guest-layout>
