@php
    $facility = $qcFacility ?? null;
    $isEdit = $facility !== null;

    $inputClass = 'mt-2 block w-full rounded-xl border-gray-200 bg-white
        text-sm text-gray-700 shadow-sm transition
        focus:border-blue-500 focus:ring-blue-500
        dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100';

    $errorInputClass = 'border-red-300 focus:border-red-500
        focus:ring-red-500 dark:border-red-700';
@endphp

<div class="space-y-6">

    {{-- Informasi Master --}}
    <div
        class="rounded-3xl border border-white/70
            bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0
                    items-center justify-center rounded-2xl
                    bg-blue-50 text-blue-600
                    dark:bg-blue-500/15 dark:text-blue-300"
            >
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </span>

            <div>
                <h2
                    class="text-lg font-bold
                        text-gray-900 dark:text-white"
                >
                    Informasi Master Fasilitas
                </h2>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Informasi umum yang berlaku untuk seluruh
                    unit/perangkat dalam jenis fasilitas ini.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2">

            {{-- Kategori --}}
            <div>
                <label
                    for="category_id"
                    class="block text-sm font-semibold
                        text-gray-700 dark:text-gray-200"
                >
                    Kategori
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required
                    class="{{ $inputClass }}
                        @error('category_id')
                            {{ $errorInputClass }}
                        @enderror"
                >
                    <option value="">
                        Pilih kategori fasilitas
                    </option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected(
                                (string) old(
                                    'category_id',
                                    $facility?->category_id
                                ) ===
                                (string) $category->id
                            )
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <p
                        class="mt-2 text-xs font-medium
                            text-red-600 dark:text-red-400"
                    >
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nama --}}
            <div>
                <label
                    for="name"
                    class="block text-sm font-semibold
                        text-gray-700 dark:text-gray-200"
                >
                    Nama Fasilitas / Alat
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    required
                    maxlength="255"
                    value="{{ old(
                        'name',
                        $facility?->name
                    ) }}"
                    placeholder="Contoh: Timbangan Gantung"
                    class="{{ $inputClass }}
                        @error('name')
                            {{ $errorInputClass }}
                        @enderror"
                >

                @error('name')
                    <p
                        class="mt-2 text-xs font-medium
                            text-red-600 dark:text-red-400"
                    >
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Merk --}}
            <div>
                <label
                    for="brand"
                    class="block text-sm font-semibold
                        text-gray-700 dark:text-gray-200"
                >
                    Merk
                </label>

                <input
                    id="brand"
                    type="text"
                    name="brand"
                    maxlength="255"
                    value="{{ old(
                        'brand',
                        $facility?->brand
                    ) }}"
                    placeholder="Contoh: TEKIRO"
                    class="{{ $inputClass }}
                        @error('brand')
                            {{ $errorInputClass }}
                        @enderror"
                >

                @error('brand')
                    <p
                        class="mt-2 text-xs font-medium
                            text-red-600 dark:text-red-400"
                    >
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Model --}}
            <div>
                <label
                    for="model"
                    class="block text-sm font-semibold
                        text-gray-700 dark:text-gray-200"
                >
                    Tipe / Model
                </label>

                <input
                    id="model"
                    type="text"
                    name="model"
                    maxlength="255"
                    value="{{ old(
                        'model',
                        $facility?->model
                    ) }}"
                    placeholder="Masukkan tipe/model"
                    class="{{ $inputClass }}
                        @error('model')
                            {{ $errorInputClass }}
                        @enderror"
                >

                @error('model')
                    <p
                        class="mt-2 text-xs font-medium
                            text-red-600 dark:text-red-400"
                    >
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Notice --}}
        <div
            class="mt-6 rounded-2xl border
                border-blue-100 bg-blue-50
                px-4 py-4
                dark:border-blue-900/40
                dark:bg-blue-900/20"
        >
            <div class="flex items-start gap-3">
                <i
                    class="fa-solid fa-circle-info
                        mt-0.5 text-blue-600
                        dark:text-blue-400"
                ></i>

                <div>
                    <p
                        class="text-sm font-semibold
                            text-blue-800
                            dark:text-blue-300"
                    >
                        Data perangkat fisik dikelola
                        secara terpisah
                    </p>

                    <p
                        class="mt-1 text-xs leading-5
                            text-blue-700
                            dark:text-blue-400"
                    >
                        Nomor inventaris, serial number,
                        lokasi, kondisi, dan kalibrasi
                        dicatat pada masing-masing Unit
                        setelah master fasilitas dibuat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Spesifikasi --}}
    <div
        class="rounded-3xl border border-white/70
            bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0
                    items-center justify-center rounded-2xl
                    bg-violet-50 text-violet-600
                    dark:bg-violet-500/15
                    dark:text-violet-300"
            >
                <i class="fa-solid fa-list-check"></i>
            </span>

            <div>
                <h2
                    class="text-lg font-bold
                        text-gray-900 dark:text-white"
                >
                    Spesifikasi Teknis
                </h2>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Spesifikasi umum yang berlaku
                    untuk jenis fasilitas ini.
                </p>
            </div>
        </div>

        <div class="mt-6">
            <label
                for="technical_specifications"
                class="block text-sm font-semibold
                    text-gray-700 dark:text-gray-200"
            >
                Daftar Spesifikasi
            </label>

            <textarea
                id="technical_specifications"
                name="technical_specifications"
                rows="12"
                placeholder="Kapasitas: 100 kg&#10;Resolusi: 0.1 kg&#10;Akurasi: ±0.5%"
                class="{{ $inputClass }}
                    resize-y font-mono leading-6
                    @error('technical_specifications')
                        {{ $errorInputClass }}
                    @enderror"
            >{{ old(
                'technical_specifications',
                $facility?->technical_specifications
            ) }}</textarea>

            <div
                class="mt-3 rounded-xl border
                    border-blue-100 bg-blue-50
                    px-4 py-3 text-xs leading-5
                    text-blue-700
                    dark:border-blue-900/40
                    dark:bg-blue-900/20
                    dark:text-blue-300"
            >
                <i
                    class="fa-solid
                        fa-circle-info mr-1.5"
                ></i>

                Gunakan format:

                <span class="font-semibold">
                    Nama spesifikasi: Nilai
                </span>

                pada setiap baris.
            </div>

            @error('technical_specifications')
                <p
                    class="mt-2 text-xs font-medium
                        text-red-600 dark:text-red-400"
                >
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="mt-5">
            <label
                for="description"
                class="block text-sm font-semibold
                    text-gray-700 dark:text-gray-200"
            >
                Keterangan Tambahan
            </label>

            <textarea
                id="description"
                name="description"
                rows="5"
                placeholder="Fungsi, penggunaan, atau informasi tambahan fasilitas."
                class="{{ $inputClass }}
                    resize-y
                    @error('description')
                        {{ $errorInputClass }}
                    @enderror"
            >{{ old(
                'description',
                $facility?->description
            ) }}</textarea>

            @error('description')
                <p
                    class="mt-2 text-xs font-medium
                        text-red-600 dark:text-red-400"
                >
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- Foto --}}
    <div
        class="rounded-3xl border border-white/70
            bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0
                    items-center justify-center rounded-2xl
                    bg-amber-50 text-amber-600
                    dark:bg-amber-500/15
                    dark:text-amber-300"
            >
                <i class="fa-solid fa-image"></i>
            </span>

            <div>
                <h2
                    class="text-lg font-bold
                        text-gray-900 dark:text-white"
                >
                    Foto Fasilitas
                </h2>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Foto representatif untuk master fasilitas.
                </p>
            </div>
        </div>

        @if ($isEdit && $facility->photo_url)
            <div class="mt-6">
                <p
                    class="mb-2 text-sm font-semibold
                        text-gray-700 dark:text-gray-200"
                >
                    Foto Saat Ini
                </p>

                <img
                    src="{{ $facility->photo_url }}"
                    alt="{{ $facility->name }}"
                    class="h-48 w-auto rounded-2xl
                        border border-gray-200
                        object-cover
                        dark:border-gray-700"
                >
            </div>
        @endif

        <div class="mt-6">
            <label
                for="photo"
                class="block text-sm font-semibold
                    text-gray-700 dark:text-gray-200"
            >
                {{ $isEdit
                    ? 'Ganti Foto'
                    : 'Foto Fasilitas' }}

                @unless ($isEdit)
                    <span class="text-red-500">*</span>
                @endunless
            </label>

            <input
                id="photo"
                name="photo"
                type="file"
                accept=".jpg,.jpeg,.png,.webp"
                @required(!$isEdit)
                class="mt-2 block w-full rounded-xl
                    border border-gray-200
                    bg-white text-sm text-gray-700
                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-gray-300"
            >

            <p
                class="mt-2 text-xs
                    text-gray-400"
            >
                JPG, JPEG, PNG atau WebP.
                Maksimal 5 MB.
            </p>

            @error('photo')
                <p
                    class="mt-2 text-xs font-medium
                        text-red-600 dark:text-red-400"
                >
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- Actions --}}
    <div
        class="flex flex-col-reverse gap-3
            sm:flex-row sm:justify-end"
    >
        <a
            href="{{ $isEdit
                ? route(
                    'qc-facilities.show',
                    $facility
                )
                : route('qc-facilities.index') }}"
            class="inline-flex items-center
                justify-center rounded-xl
                border border-gray-200 bg-white
                px-5 py-2.5 text-sm font-semibold
                text-gray-700 shadow-sm
                hover:bg-gray-50
                dark:border-gray-700
                dark:bg-gray-800
                dark:text-gray-200"
        >
            Batal
        </a>

        <button
            type="submit"
            class="inline-flex items-center
                justify-center rounded-xl
                bg-blue-600 px-5 py-2.5
                text-sm font-semibold text-white
                shadow-sm hover:bg-blue-700"
        >
            <i class="fa-solid fa-floppy-disk mr-2"></i>

            {{ $isEdit
                ? 'Simpan Perubahan'
                : 'Simpan Fasilitas' }}
        </button>
    </div>
</div>
