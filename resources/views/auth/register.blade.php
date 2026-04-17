<x-guest-layout>
    <div class="w-full max-w-[480px]">

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

            {{-- Heading --}}
            <div class="anim-fade-up delay-150 mb-7">
                <h2 class="font-['DM_Serif_Display'] text-[24px] text-[#0b1d3e] font-normal">Buat Akun Baru</h2>
                <p class="text-[13px] font-light text-slate-400 mt-1 leading-relaxed">
                    Lengkapi data diri Anda untuk mendaftar ke sistem PADUKA
                </p>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
            <div class="anim-fade-up mb-6 flex gap-3 border border-red-200 bg-red-50 rounded-2xl px-4 py-3.5">
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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- ── Section: Identitas ── --}}
                <div class="mb-5">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-id-card text-[#1e40af] text-[10px]"></i>
                        <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Identitas</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>

                    <div class="space-y-4">
                        <div class="anim-fade-up delay-150">
                            <label for="name" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                   required autofocus autocomplete="name"
                                   placeholder="Masukkan nama lengkap"
                                   class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                        </div>

                        <div class="anim-fade-up delay-200">
                            <label for="username" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">NIP</label>
                            <input id="username" type="text" name="username" value="{{ old('username') }}"
                                   required
                                   placeholder="Nomor Induk Pegawai"
                                   class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                            <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
                        </div>

                        <div class="anim-fade-up delay-250">
                            <label for="email" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Alamat Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   required autocomplete="username"
                                   placeholder="nama@perusahaan.com"
                                   class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>
                    </div>
                </div>

                {{-- ── Section: Organisasi ── --}}
                <div class="mb-5">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-building text-[#1e40af] text-[10px]"></i>
                        <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Organisasi</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>

                    <div class="space-y-4">
                        <div class="anim-fade-up delay-200">
                            <label for="jabatan" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Jabatan</label>
                            <input id="jabatan" type="text" name="jabatan" value="{{ old('jabatan') }}"
                                   placeholder="Jabatan Anda"
                                   class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-1.5" />
                        </div>

                        <div class="grid grid-cols-2 gap-3 anim-fade-up delay-250">
                            <div>
                                <label for="unit_kerja" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Unit Kerja</label>
                                <input id="unit_kerja" type="text" name="unit_kerja" value="{{ old('unit_kerja') }}"
                                       placeholder="Unit kerja"
                                       class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                                <x-input-error :messages="$errors->get('unit_kerja')" class="mt-1.5" />
                            </div>
                            <div>
                                <label for="departemen" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Departemen</label>
                                <input id="departemen" type="text" name="departemen" value="{{ old('departemen') }}"
                                       placeholder="Departemen"
                                       class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                                <x-input-error :messages="$errors->get('departemen')" class="mt-1.5" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 anim-fade-up delay-300">
                            <div>
                                <label for="divisi" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Divisi</label>
                                <input id="divisi" type="text" name="divisi" value="{{ old('divisi') }}"
                                       placeholder="Divisi"
                                       class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                                <x-input-error :messages="$errors->get('divisi')" class="mt-1.5" />
                            </div>
                            <div>
                                <label for="no_telp" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">No. Telepon</label>
                                <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}"
                                       placeholder="08xxxxxxxxxx"
                                       class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                                <x-input-error :messages="$errors->get('no_telp')" class="mt-1.5" />
                            </div>
                        </div>

                        <div class="anim-fade-up delay-300">
                            <label for="level" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Level Akses</label>
                            <div class="relative">
                                <select id="level" name="level"
                                        class="auth-input w-full appearance-none border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 shadow-inner pr-10 cursor-pointer">
                                    <option value="user"    {{ old('level') == 'user'    ? 'selected' : '' }}>User</option>
                                    <option value="manager" {{ old('level') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    {{-- <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option> --}}
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                            </div>
                            <x-input-error :messages="$errors->get('level')" class="mt-1.5" />
                        </div>
                    </div>
                </div>

                {{-- ── Section: Keamanan ── --}}
                <div class="mb-7">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-shield-halved text-[#1e40af] text-[10px]"></i>
                        <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Keamanan</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>

                    <div class="space-y-4">
                        <div class="anim-fade-up delay-300">
                            <label for="password" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Password</label>
                            <input id="password" type="password" name="password"
                                   required autocomplete="new-password"
                                   placeholder="Minimal 8 karakter"
                                   class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        <div class="anim-fade-up delay-400">
                            <label for="password_confirmation" class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   required autocomplete="new-password"
                                   placeholder="Ulangi password"
                                   class="auth-input w-full border border-slate-200 bg-slate-50/70 rounded-xl px-4 py-3 text-[13.5px] text-slate-700 placeholder-slate-300 shadow-inner">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="anim-fade-up delay-500 flex items-center justify-between gap-4">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-1.5 text-[12.5px] text-[#1e40af]/70 hover:text-[#1e40af] font-medium transition-colors duration-150 whitespace-nowrap">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Sudah punya akun?</span>
                    </a>
                    <button type="submit"
                            class="group relative flex items-center gap-2 overflow-hidden bg-[#0b1d3e] hover:bg-[#0f2552] text-white font-semibold text-[13.5px] tracking-wide rounded-xl px-6 py-3 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(11,29,62,0.3)] shadow-[0_2px_8px_rgba(11,29,62,0.2)] active:translate-y-0">
                        <span class="absolute inset-0 bg-gradient-to-br from-white/[0.06] to-transparent pointer-events-none"></span>
                        <i class="fa-solid fa-user-plus text-[12px]"></i>
                        <span>Daftar</span>
                    </button>
                </div>

            </form>
        </div>

        <p class="text-center text-[11px] font-light text-slate-300 tracking-wide mt-6">
            © {{ date('Y') }} PT. Rekaindo Global Jasa · All rights reserved
        </p>
    </div>
</x-guest-layout>
