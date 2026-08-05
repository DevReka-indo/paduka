@props([
    'workIndicator' => null,
    'action',
    'method' => 'POST',
    'submitLabel' => 'Simpan Indikator',
    'selectedType' => null,
])

@php
    $isEdit = filled($workIndicator);

    $type = old(
        'type',
        $workIndicator?->type
            ?? $selectedType
            ?? \App\Models\WorkIndicator::TYPE_PROGRAM_KERJA
    );

    $name = old(
        'name',
        $workIndicator?->name
    );

    $description = old(
        'description',
        $workIndicator?->description
    );

    $sortOrder = old(
        'sort_order',
        $workIndicator?->sort_order ?? 0
    );

    $isActive = (bool) old(
        'is_active',
        $workIndicator?->is_active ?? true
    );
@endphp

<form
    method="POST"
    action="{{ $action }}"
>
    @csrf

    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

        {{-- Header --}}
        <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                {{ $isEdit ? 'Edit Indikator' : 'Tambah Indikator' }}
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{
                    $isEdit
                        ? 'Perbarui informasi dan status indikator.'
                        : 'Lengkapi informasi indikator yang akan ditampilkan.'
                }}
            </p>
        </div>

        <div class="space-y-6 p-6">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 dark:border-red-900/40 dark:bg-red-900/20">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>

                        <div>
                            <p class="text-sm font-semibold text-red-700 dark:text-red-300">
                                Data belum dapat disimpan.
                            </p>

                            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Jenis --}}
            <div>
                <label
                    for="type"
                    class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                >
                    Jenis Indikator
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="type"
                    name="type"
                    class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    required
                >
                    @foreach (\App\Models\WorkIndicator::typeOptions() as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected($type === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <p class="mt-1.5 text-xs text-gray-400">
                    Program Kerja menggunakan input bulanan, sedangkan KPI menggunakan input triwulan.
                </p>
            </div>

            {{-- Nama --}}
            <div>
                <label
                    for="name"
                    class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                >
                    Nama Indikator
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ $name }}"
                    maxlength="255"
                    placeholder="Masukkan nama indikator"
                    class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    required
                >

                @error('name')
                    <p class="mt-1.5 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label
                    for="description"
                    class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                >
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Tambahkan penjelasan indikator jika diperlukan"
                    class="w-full resize-y rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >{{ $description }}</textarea>

                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Urutan --}}
            <div>
                <label
                    for="sort_order"
                    class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                >
                    Urutan Tampilan
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="sort_order"
                    type="number"
                    name="sort_order"
                    value="{{ $sortOrder }}"
                    min="0"
                    class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    required
                >

                <p class="mt-1.5 text-xs text-gray-400">
                    Nilai yang lebih kecil ditampilkan lebih dahulu.
                </p>

                @error('sort_order')
                    <p class="mt-1.5 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                <div class="flex items-start gap-3">
                    <input
                        type="hidden"
                        name="is_active"
                        value="0"
                    >

                    <input
                        id="is_active"
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked($isActive)
                        class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800"
                    >

                    <label
                        for="is_active"
                        class="cursor-pointer"
                    >
                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">
                            Indikator aktif
                        </span>

                        <span class="mt-1 block text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                            Indikator aktif ditampilkan pada monitoring dan halaman input capaian.
                        </span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex flex-col-reverse gap-2 border-t border-gray-100 px-6 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-end">
            <a
                href="{{ route('work-program-kpi.indicators.index', [
                    'type' => $type,
                ]) }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                {{ $submitLabel }}
            </button>
        </div>
    </div>
</form>
