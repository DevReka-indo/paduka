<x-guest-layout>
    <div class="w-full max-w-[420px]">

        <div class="anim-fade-in flex justify-center mb-8">
            <img src="{{ asset('img/logo-paduka.svg') }}" alt="{{ config('app.name') }}"
                 class="w-20 object-contain opacity-90">
        </div>

        <div class="anim-fade-up delay-100 relative bg-white border border-slate-200 rounded-3xl px-8 py-9 shadow-[0_8px_40px_rgba(11,29,62,0.08),0_1px_3px_rgba(0,0,0,0.04)]">
            <div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-[#1e40af]/30 to-transparent"></div>

            {{-- Icon with pulse ring --}}
            <div class="anim-fade-up delay-150 flex justify-center mb-6">
                <div class="relative">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shadow-sm">
                        <i class="fa-regular fa-envelope text-[#1e40af] text-2xl"></i>
                    </div>
                    <span class="absolute inset-0 rounded-2xl border border-blue-300 animate-ping opacity-30"></span>
                </div>
            </div>

            <div class="anim-fade-up delay-200 text-center mb-6">
                <h2 class="font-['DM_Serif_Display'] text-[24px] text-[#0b1d3e] font-normal">Verifikasi Email</h2>
                <p class="text-[13px] font-light text-slate-400 mt-2 leading-relaxed max-w-[300px] mx-auto">
                    Terima kasih sudah mendaftar! Silakan verifikasi alamat email Anda dengan mengklik tautan yang telah kami kirimkan.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
            <div class="anim-fade-up mb-5 flex gap-3 border border-emerald-200 bg-emerald-50 rounded-2xl px-4 py-3.5">
                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                <p class="text-[12.5px] text-emerald-700 font-light leading-relaxed">
                    Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
                </p>
            </div>
            @endif

            <div class="anim-fade-up delay-300 flex flex-col sm:flex-row items-center gap-3">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="group relative w-full flex items-center justify-center gap-2 overflow-hidden bg-[#0b1d3e] hover:bg-[#0f2552] text-white font-semibold text-[13px] tracking-wide rounded-xl px-5 py-3 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(11,29,62,0.3)] shadow-[0_2px_8px_rgba(11,29,62,0.2)] active:translate-y-0">
                        <span class="absolute inset-0 bg-gradient-to-br from-white/[0.06] to-transparent pointer-events-none"></span>
                        <i class="fa-solid fa-rotate-right text-[11px]"></i>
                        <span>Kirim Ulang Email</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 border border-slate-200 text-slate-500 hover:text-[#0b1d3e] hover:border-slate-300 font-medium text-[13px] rounded-xl px-5 py-3 transition-all duration-200 bg-white hover:bg-slate-50">
                        <i class="fa-solid fa-right-from-bracket text-[11px]"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-[11px] font-light text-slate-300 tracking-wide mt-6">
            © {{ date('Y') }} PT. Rekaindo Global Jasa · All rights reserved
        </p>
    </div>
</x-guest-layout>
