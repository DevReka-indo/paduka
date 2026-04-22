<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data"
        class="space-y-6" x-data="fotoUpload()">
        @csrf
        @method('patch')

        {{-- Foto Profil --}}
        <div class="flex items-center gap-5 pb-6 border-b border-gray-100 dark:border-gray-700">
            <div class="relative group cursor-pointer flex-shrink-0" @click="$refs.fotoInput.click()">
                <img :src="preview" alt="Foto Profil"
                    class="w-20 h-20 rounded-2xl object-cover border-2 transition bg-gray-50 dark:bg-gray-900"
                    :class="hasFile ? 'border-indigo-400' : 'border-gray-200 dark:border-gray-600'" />
                <div class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center
                            opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png,image/webp"
                    class="hidden" x-ref="fotoInput" @change="onFileChange($event)" />
            </div>

            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Foto Profil</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                    Klik foto untuk mengganti. Format: jpg, png, webp. Maks 2MB.
                </p>

                <span class="inline-block mt-2 text-xs bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-2.5 py-1 rounded-full">
                    {{ $user->level ?? 'User' }}
                </span>

                <p x-show="hasFile" x-transition class="mt-1.5 text-xs text-indigo-500 dark:text-indigo-400 font-medium">
                    ✓ Foto baru dipilih — klik "Simpan Perubahan" untuk menyimpan
                </p>

                @error('foto')
                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Fields Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="username" :value="__('Username / NIP')" />
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                    :value="old('username', $user->username)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    :value="old('email', $user->email)" required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="no_telp" :value="__('No. Telepon')" />
                <x-text-input id="no_telp" name="no_telp" type="text" class="mt-1 block w-full"
                    :value="old('no_telp', $user->no_telp)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('no_telp')" />
            </div>

            <div>
                <x-input-label for="jabatan" :value="__('Jabatan')" />
                <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full"
                    :value="old('jabatan', $user->jabatan)" />
                <x-input-error class="mt-2" :messages="$errors->get('jabatan')" />
            </div>

            <div>
                <x-input-label for="departemen" :value="__('Departemen')" />
                <x-text-input id="departemen" name="departemen" type="text" class="mt-1 block w-full"
                    :value="old('departemen', $user->departemen)" />
                <x-input-error class="mt-2" :messages="$errors->get('departemen')" />
            </div>

            <div>
                <x-input-label for="divisi" :value="__('Divisi')" />
                <x-text-input id="divisi" name="divisi" type="text" class="mt-1 block w-full"
                    :value="old('divisi', $user->divisi)" />
                <x-input-error class="mt-2" :messages="$errors->get('divisi')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="level" :value="__('Level')" />
                <x-text-input id="level" type="text"
                    class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed"
                    :value="old('level', $user->level)" readonly />
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Level akun hanya dapat diubah oleh admin.</p>
            </div>

        </div>

        {{-- Unit Kerja --}}
        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
            <x-input-label :value="__('Unit Kerja')" />
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 mb-3">Unit kerja hanya dapat diubah oleh admin.</p>

            @php $userUnitKerja = $user->unitKerja; @endphp

            @if($userUnitKerja->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Belum ada unit kerja yang ditetapkan.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($userUnitKerja as $unit)
                        <div class="flex items-center gap-2 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40 px-3 py-2 rounded-xl">
                            <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Email Verification Notice --}}
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-xl p-4">
                <p class="text-sm text-amber-700 dark:text-amber-300">
                    {{ __('Alamat email Anda belum terverifikasi.') }}
                    <button form="send-verification"
                        class="underline font-medium hover:text-amber-900 dark:hover:text-amber-200 focus:outline-none">
                        {{ __('Klik di sini untuk kirim ulang email verifikasi.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                        {{ __('Link verifikasi baru telah dikirim ke email Anda.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<script>
function fotoUpload() {
    return {
        preview: '{{ $user->foto ? asset("storage/" . $user->foto) : asset("img/user-default.svg") }}',
        originalSrc: '{{ $user->foto ? asset("storage/" . $user->foto) : asset("img/user-default.svg") }}',
        hasFile: false,
        onFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.hasFile = true;
            const reader = new FileReader();
            reader.onload = (e) => { this.preview = e.target.result; };
            reader.readAsDataURL(file);
        },
    }
}
</script>
