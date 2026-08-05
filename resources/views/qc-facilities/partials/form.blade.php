@php
    $facility = $qcFacility ?? null;
    $isEdit = $facility !== null;

    $conditionOptions = [
        'baik' => 'Baik',
        'perlu_perbaikan' => 'Perlu Perbaikan',
        'dalam_perbaikan' => 'Dalam Perbaikan',
        'tidak_layak' => 'Tidak Layak Digunakan',
    ];

    $inputClass = 'mt-2 block w-full rounded-xl border-gray-200 bg-white text-sm text-gray-700 shadow-sm
        transition focus:border-blue-500 focus:ring-blue-500
        dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100';

    $errorInputClass = 'border-red-300 focus:border-red-500 focus:ring-red-500
        dark:border-red-700';
@endphp

<div class="space-y-6">

    {{-- Informasi Utama --}}
    <div
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900">

        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-blue-50 text-blue-600
                    dark:bg-blue-500/15 dark:text-blue-300">

                <i class="fa-solid fa-screwdriver-wrench"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Informasi Utama Fasilitas
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Isi kategori, nama, merk, tipe, dan identitas fasilitas Quality Control.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2">

            {{-- Kategori --}}
            <div>
                <label
                    for="category_id"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Kategori
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required
                    class="{{ $inputClass }} @error('category_id') {{ $errorInputClass }} @enderror"
                >
                    <option value="">
                        Pilih kategori fasilitas
                    </option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected(
                                (string) old('category_id', $facility?->category_id) ===
                                (string) $category->id
                            )
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nama Alat --}}
            <div>
                <label
                    for="name"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Nama Fasilitas/Alat
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $facility?->name) }}"
                    required
                    maxlength="255"
                    placeholder="Contoh: Temperature & Humidity Chamber"
                    class="{{ $inputClass }} @error('name') {{ $errorInputClass }} @enderror"
                >

                @error('name')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Merk --}}
            <div>
                <label
                    for="brand"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Merk
                </label>

                <input
                    id="brand"
                    type="text"
                    name="brand"
                    value="{{ old('brand', $facility?->brand) }}"
                    maxlength="255"
                    placeholder="Contoh: ASLI"
                    class="{{ $inputClass }} @error('brand') {{ $errorInputClass }} @enderror"
                >

                @error('brand')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Model --}}
            <div>
                <label
                    for="model"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Tipe/Model
                </label>

                <input
                    id="model"
                    type="text"
                    name="model"
                    value="{{ old('model', $facility?->model) }}"
                    maxlength="255"
                    placeholder="Contoh: TH-1000-D"
                    class="{{ $inputClass }} @error('model') {{ $errorInputClass }} @enderror"
                >

                @error('model')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nomor Inventaris --}}
            <div>
                <label
                    for="inventory_number"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Nomor Inventaris
                </label>

                <input
                    id="inventory_number"
                    type="text"
                    name="inventory_number"
                    value="{{ old('inventory_number', $facility?->inventory_number) }}"
                    maxlength="255"
                    placeholder="Masukkan nomor inventaris"
                    class="{{ $inputClass }} @error('inventory_number') {{ $errorInputClass }} @enderror"
                >

                @error('inventory_number')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Serial Number --}}
            <div>
                <label
                    for="serial_number"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Serial Number
                </label>

                <input
                    id="serial_number"
                    type="text"
                    name="serial_number"
                    value="{{ old('serial_number', $facility?->serial_number) }}"
                    maxlength="255"
                    placeholder="Masukkan serial number"
                    class="{{ $inputClass }} @error('serial_number') {{ $errorInputClass }} @enderror"
                >

                @error('serial_number')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Lokasi --}}
            <div>
                <label
                    for="location"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Lokasi Fasilitas
                </label>

                <input
                    id="location"
                    type="text"
                    name="location"
                    value="{{ old('location', $facility?->location) }}"
                    maxlength="255"
                    placeholder="Contoh: Laboratorium Quality Control"
                    class="{{ $inputClass }} @error('location') {{ $errorInputClass }} @enderror"
                >

                @error('location')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kondisi --}}
            <div>
                <label
                    for="condition"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Kondisi Alat
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="condition"
                    name="condition"
                    required
                    class="{{ $inputClass }} @error('condition') {{ $errorInputClass }} @enderror"
                >
                    @foreach ($conditionOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old('condition', $facility?->condition ?? 'baik') === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('condition')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Spesifikasi --}}
    <div
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900">

        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-violet-50 text-violet-600
                    dark:bg-violet-500/15 dark:text-violet-300">

                <i class="fa-solid fa-list-check"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Spesifikasi Teknis
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Tuliskan satu spesifikasi pada setiap baris menggunakan format
                    <strong>Nama spesifikasi: Nilai</strong>.
                </p>
            </div>
        </div>

        <div class="mt-6">
            <label
                for="technical_specifications"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
            >
                Daftar Spesifikasi
            </label>

            <textarea
                id="technical_specifications"
                name="technical_specifications"
                rows="12"
                placeholder="Temperature range: -40°C hingga 150°C&#10;Humidity range: 20% hingga 98% RH&#10;Resolution: 0.1°C, 0.1% RH"
                class="{{ $inputClass }} resize-y font-mono leading-6 @error('technical_specifications') {{ $errorInputClass }} @enderror"
            >{{ old('technical_specifications', $facility?->technical_specifications) }}</textarea>

            <div
                class="mt-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3
                    text-xs leading-5 text-blue-700
                    dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300"
            >
                <i class="fa-solid fa-circle-info mr-1.5"></i>

                Contoh:
                <span class="font-semibold">
                    Akurasi: ±0.5%
                </span>.
                Format ini akan ditampilkan secara otomatis sebagai label dan nilai pada card fasilitas.
            </div>

            @error('technical_specifications')
                <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="mt-5">
            <label
                for="description"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
            >
                Keterangan Tambahan
            </label>

            <textarea
                id="description"
                name="description"
                rows="5"
                placeholder="Tambahkan informasi penggunaan, fungsi alat, atau catatan lainnya."
                class="{{ $inputClass }} resize-y @error('description') {{ $errorInputClass }} @enderror"
            >{{ old('description', $facility?->description) }}</textarea>

            @error('description')
                <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- Kalibrasi --}}
    <div
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900">

        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-emerald-50 text-emerald-600
                    dark:bg-emerald-500/15 dark:text-emerald-300">

                <i class="fa-solid fa-calendar-check"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Informasi Kalibrasi
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Status kalibrasi akan dihitung otomatis berdasarkan masa berlaku.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2">

            {{-- Tanggal Kalibrasi --}}
            <div>
                <label
                    for="calibration_date"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Tanggal Kalibrasi
                </label>

                <input
                    id="calibration_date"
                    type="date"
                    name="calibration_date"
                    value="{{ old(
                        'calibration_date',
                        $facility?->calibration_date?->format('Y-m-d')
                    ) }}"
                    class="{{ $inputClass }} @error('calibration_date') {{ $errorInputClass }} @enderror"
                >

                @error('calibration_date')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Masa Berlaku --}}
            <div>
                <label
                    for="calibration_valid_until"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Masa Berlaku Kalibrasi
                </label>

                <input
                    id="calibration_valid_until"
                    type="date"
                    name="calibration_valid_until"
                    value="{{ old(
                        'calibration_valid_until',
                        $facility?->calibration_valid_until?->format('Y-m-d')
                    ) }}"
                    class="{{ $inputClass }} @error('calibration_valid_until') {{ $errorInputClass }} @enderror"
                >

                @error('calibration_valid_until')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Foto --}}
    <div
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900">

        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-amber-50 text-amber-600
                    dark:bg-amber-500/15 dark:text-amber-300">

                <i class="fa-solid fa-image"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Foto Fasilitas
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gunakan foto alat yang jelas dengan format JPG, JPEG, PNG, atau WebP.
                </p>
            </div>
        </div>

        <div
            class="mt-6"
            x-data="{
                preview: @js($facility?->photo_url),
                fileName: '',
                handleFile(event) {
                    const file = event.target.files[0];

                    if (!file) {
                        this.fileName = '';
                        return;
                    }

                    this.fileName = file.name;

                    const reader = new FileReader();

                    reader.onload = (result) => {
                        this.preview = result.target.result;
                    };

                    reader.readAsDataURL(file);
                }
            }"
        >
            <div class="grid gap-6 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)]">

                {{-- Preview --}}
                <div
                    class="relative aspect-[4/3] overflow-hidden rounded-3xl
                        border border-dashed border-gray-300 bg-slate-50
                        dark:border-gray-700 dark:bg-gray-800"
                >
                    <template x-if="preview">
                        <img
                            :src="preview"
                            alt="Preview foto fasilitas"
                            class="h-full w-full object-cover"
                        >
                    </template>

                    <template x-if="!preview">
                        <div
                            class="flex h-full w-full flex-col items-center justify-center
                                px-6 text-center text-gray-400 dark:text-gray-500"
                        >
                            <span
                                class="flex h-16 w-16 items-center justify-center
                                    rounded-3xl bg-white text-2xl shadow-sm
                                    ring-1 ring-gray-200
                                    dark:bg-white/10 dark:ring-white/10"
                            >
                                <i class="fa-solid fa-camera"></i>
                            </span>

                            <p class="mt-3 text-sm font-semibold">
                                Belum ada foto dipilih
                            </p>
                        </div>
                    </template>
                </div>

                {{-- Upload --}}
                <div class="flex flex-col justify-center">
                    <label
                        for="photo"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                    >
                        Pilih Foto

                        @unless ($isEdit)
                            <span class="text-red-500">*</span>
                        @endunless
                    </label>

                    <label
                        for="photo"
                        class="mt-2 flex cursor-pointer flex-col items-center justify-center
                            rounded-2xl border-2 border-dashed border-gray-300
                            bg-slate-50 px-6 py-8 text-center transition
                            hover:border-blue-400 hover:bg-blue-50
                            dark:border-gray-700 dark:bg-gray-800
                            dark:hover:border-blue-500 dark:hover:bg-blue-500/10"
                    >
                        <span
                            class="flex h-12 w-12 items-center justify-center
                                rounded-2xl bg-white text-blue-600 shadow-sm
                                ring-1 ring-gray-200
                                dark:bg-white/10 dark:text-blue-300 dark:ring-white/10"
                        >
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </span>

                        <span class="mt-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                            Klik untuk memilih foto
                        </span>

                        <span class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            Maksimal 5 MB
                        </span>

                        <span
                            x-show="fileName"
                            x-text="fileName"
                            class="mt-3 max-w-full truncate rounded-lg bg-blue-50
                                px-3 py-1.5 text-xs font-semibold text-blue-700
                                dark:bg-blue-500/15 dark:text-blue-300"
                        ></span>
                    </label>

                    <input
                        id="photo"
                        type="file"
                        name="photo"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        @change="handleFile($event)"
                        @unless ($isEdit) required @endunless
                        class="sr-only"
                    >

                    @if ($isEdit)
                        <p class="mt-3 text-xs leading-5 text-gray-500 dark:text-gray-400">
                            Biarkan kosong apabila foto lama tidak ingin diganti.
                        </p>
                    @endif

                    @error('photo')
                        <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div
        class="flex flex-col-reverse gap-3 rounded-3xl border border-white/70
            bg-white p-5 shadow-sm
            dark:border-gray-800 dark:bg-gray-900
            sm:flex-row sm:items-center sm:justify-end"
    >
        <a
            href="{{ $isEdit
                ? route('qc-facilities.show', $facility)
                : route('qc-facilities.index') }}"
            class="inline-flex items-center justify-center rounded-xl
                border border-gray-200 bg-white px-5 py-2.5
                text-sm font-semibold text-gray-700 shadow-sm transition
                hover:bg-gray-50
                dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200
                dark:hover:bg-gray-700"
        >
            <i class="fa-solid fa-xmark mr-2"></i>
            Batal
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-xl
                bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white
                shadow-sm transition hover:bg-blue-700"
        >
            <i class="fa-solid fa-floppy-disk mr-2"></i>

            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}
        </button>
    </div>
</div>
