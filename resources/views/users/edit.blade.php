@extends('layouts.app')

@section('header')
    Edit Pengguna
@endsection

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">

    <a href="{{ route('users.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar pengguna
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="w-8 h-8 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Edit Pengguna — {{ $user->name }}</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Kosongkan password jika tidak ingin mengubahnya</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data"
            class="p-6 space-y-6" x-data="fotoUpload()">
            @csrf
            @method('PUT')

            {{-- Foto --}}
            <div class="flex items-center gap-5 pb-6 border-b border-gray-100 dark:border-gray-700">
                <div class="relative group cursor-pointer flex-shrink-0" @click="$refs.fotoInput.click()">
                    <img :src="preview" alt="Foto"
                        class="w-20 h-20 rounded-2xl object-cover border-2 transition"
                        :class="hasFile ? 'border-indigo-400' : 'border-gray-200 dark:border-gray-600'" />
                    <div class="absolute inset-0 bg-black bg-opacity-40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png,image/webp"
                        class="hidden" x-ref="fotoInput" @change="onFileChange($event)" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Foto Profil</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Klik untuk mengganti. Format: jpg, png, webp. Maks 2MB.</p>
                    <p x-show="hasFile" x-transition class="mt-1.5 text-xs text-indigo-500 dark:text-indigo-400 font-medium">✓ Foto baru dipilih</p>
                    @error('foto') <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Data Diri --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        :value="old('name', $user->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="username" value="Username / NIP" />
                    <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                        :value="old('username', $user->username)" required />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="no_telp" value="No. Telepon" />
                    <x-text-input id="no_telp" name="no_telp" type="text" class="mt-1 block w-full"
                        :value="old('no_telp', $user->no_telp)" />
                    <x-input-error :messages="$errors->get('no_telp')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Password Baru" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                        placeholder="Kosongkan jika tidak diubah" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                        class="mt-1 block w-full" placeholder="Kosongkan jika tidak diubah" />
                </div>

                <div>
                    <x-input-label for="jabatan" value="Jabatan" />
                    <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full"
                        :value="old('jabatan', $user->jabatan)" />
                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="departemen" value="Departemen" />
                    <x-text-input id="departemen" name="departemen" type="text" class="mt-1 block w-full"
                        :value="old('departemen', $user->departemen)" />
                    <x-input-error :messages="$errors->get('departemen')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="divisi" value="Divisi" />
                    <x-text-input id="divisi" name="divisi" type="text" class="mt-1 block w-full"
                        :value="old('divisi', $user->divisi)" />
                    <x-input-error :messages="$errors->get('divisi')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="level" value="Level" />
                    <select id="level" name="level"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 text-sm">
                        <option value="user"       @selected(old('level', $user->level) == 'user')>User</option>
                        <option value="manager"    @selected(old('level', $user->level) == 'manager')>Manager</option>
                        <option value="admin"      @selected(old('level', $user->level) == 'admin')>Admin</option>
                        <option value="superadmin" @selected(old('level', $user->level) == 'superadmin')>Super Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('level')" class="mt-2" />
                </div>

                <div>
                    <x-input-label value="Status Akun" />
                    <div class="mt-2 flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="keterangan" value="1"
                                class="text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:bg-gray-700 dark:border-gray-600"
                                @checked(old('keterangan', $user->keterangan) == '1') />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="keterangan" value="0"
                                class="text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:bg-gray-700 dark:border-gray-600"
                                @checked(old('keterangan', $user->keterangan) == '0') />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Nonaktif</span>
                        </label>
                    </div>
                </div>

                {{-- Unit Kerja Multi-Select --}}
                @php
                    $selectedIds = old('unit_kerja_ids', $user->unitKerja->pluck('id')->toArray());
                @endphp
                @include('partials.unit-kerja-checkboxes')

            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <x-primary-button>Simpan Perubahan</x-primary-button>
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function fotoUpload() {
    return {
        preview: '{{ $user->foto ? asset("storage/" . $user->foto) : asset("img/user-default.svg") }}',
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
@endsection
