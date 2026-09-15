@php
    $dailyCheck = $dailyCheck ?? null;
    $isEdit = $dailyCheck !== null;

    $inputClass = 'mt-2 block w-full rounded-xl border-gray-200 bg-white text-sm text-gray-700 shadow-sm
        transition focus:border-indigo-500 focus:ring-indigo-500
        dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100';

    $errorInputClass = 'border-red-300 focus:border-red-500 focus:ring-red-500
        dark:border-red-700';

    $quantityFields = [
        'visual_qty' => [
            'label' => 'Visual',
            'short' => 'VT',
            'icon' => 'fa-eye',
        ],
        'skun_qty' => [
            'label' => 'Skun',
            'short' => 'SK',
            'icon' => 'fa-plug',
        ],
        'cramping_qty' => [
            'label' => 'Cramping',
            'short' => 'CR',
            'icon' => 'fa-compress',
        ],
        'marking_qty' => [
            'label' => 'Marking',
            'short' => 'MK',
            'icon' => 'fa-tag',
        ],
        'belltest_qty' => [
            'label' => 'Belltest',
            'short' => 'BT',
            'icon' => 'fa-bell',
        ],
        'function_qty' => [
            'label' => 'Function',
            'short' => 'FT',
            'icon' => 'fa-gears',
        ],
    ];
@endphp

<div
    class="space-y-6"
    x-data="{
        projectId: @js((string) old('project_id', $dailyCheck?->project_id)),
        result: @js(old('result', $dailyCheck?->result ?? 'pending')),
        ncrNumber: @js(old('ncr_number', $dailyCheck?->ncr_number ?? '')),

        visual: Number(@js(old('visual_qty', $dailyCheck?->visual_qty ?? 0))) || 0,
        skun: Number(@js(old('skun_qty', $dailyCheck?->skun_qty ?? 0))) || 0,
        cramping: Number(@js(old('cramping_qty', $dailyCheck?->cramping_qty ?? 0))) || 0,
        marking: Number(@js(old('marking_qty', $dailyCheck?->marking_qty ?? 0))) || 0,
        belltest: Number(@js(old('belltest_qty', $dailyCheck?->belltest_qty ?? 0))) || 0,
        functionQty: Number(@js(old('function_qty', $dailyCheck?->function_qty ?? 0))) || 0,

        get totalFindings() {
            return this.visual
                + this.skun
                + this.cramping
                + this.marking
                + this.belltest
                + this.functionQty;
        },

        get productStatus() {
            if (this.result === 'ok') {
                return 'Close';
            }

            if (this.result === 'nok') {
                return 'Open';
            }

            return 'Belum Ditentukan';
        }
    }"
>
    {{-- Informasi Pemeriksaan --}}
    <section
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-indigo-50 text-indigo-600
                    dark:bg-indigo-500/15 dark:text-indigo-300"
            >
                <i class="fa-solid fa-clipboard-check"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Informasi Pemeriksaan
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Masukkan tanggal, proyek, lokasi pemeriksaan, produk, dan inspector.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            {{-- Check Date --}}
            <div>
                <label
                    for="check_date"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Check Date
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="check_date"
                    type="date"
                    name="check_date"
                    value="{{ old(
                        'check_date',
                        $dailyCheck?->check_date?->format('Y-m-d') ?? now()->format('Y-m-d')
                    ) }}"
                    required
                    class="{{ $inputClass }} @error('check_date') {{ $errorInputClass }} @enderror"
                >

                @error('check_date')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Master Project --}}
            <div>
                <label
                    for="project_id"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Proyek dari Master
                </label>

                <select
                    id="project_id"
                    name="project_id"
                    x-model="projectId"
                    class="{{ $inputClass }} @error('project_id') {{ $errorInputClass }} @enderror"
                >
                    <option value="">
                        Proyek lainnya / belum terdaftar
                    </option>

                    @foreach ($projects as $project)
                        <option
                            value="{{ $project->id }}"
                            @selected(
                                (string) old('project_id', $dailyCheck?->project_id) ===
                                (string) $project->id
                            )
                        >
                            {{ $project->kode_proyek }}
                            —
                            {{ $project->nama_proyek }}
                        </option>
                    @endforeach
                </select>

                @error('project_id')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Project Name --}}
            <div>
                <label
                    for="project_name"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Nama Proyek Lainnya
                    <span
                        x-show="!projectId"
                        class="text-red-500"
                    >*</span>
                </label>

                <input
                    id="project_name"
                    type="text"
                    name="project_name"
                    value="{{ old(
                        'project_name',
                        $dailyCheck?->project_id ? '' : $dailyCheck?->project_name
                    ) }}"
                    maxlength="255"
                    placeholder="Contoh: KCI, 612, Manggarai SS"
                    :required="!projectId"
                    :disabled="Boolean(projectId)"
                    class="{{ $inputClass }} @error('project_name') {{ $errorInputClass }} @enderror
                        disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400
                        dark:disabled:bg-gray-900"
                >

                <p
                    x-show="projectId"
                    class="mt-2 text-xs text-gray-500 dark:text-gray-400"
                >
                    Nama proyek akan diambil otomatis dari master proyek.
                </p>

                @error('project_name')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Document Check --}}
            <div>
                <label
                    for="document_check"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Doc. Check
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="document_check"
                    name="document_check"
                    required
                    class="{{ $inputClass }} @error('document_check') {{ $errorInputClass }} @enderror"
                >
                    <option value="">
                        Pilih dokumen pemeriksaan
                    </option>

                    @foreach ($documentCheckOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old('document_check', $dailyCheck?->document_check) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('document_check')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Inspection Gate --}}
            <div>
                <label
                    for="inspection_gate"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Inspection Gate
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="inspection_gate"
                    name="inspection_gate"
                    required
                    class="{{ $inputClass }} @error('inspection_gate') {{ $errorInputClass }} @enderror"
                >
                    <option value="">
                        Pilih lokasi pemeriksaan
                    </option>

                    @foreach ($inspectionGateOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old('inspection_gate', $dailyCheck?->inspection_gate) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('inspection_gate')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Check Category --}}
            <div>
                <label
                    for="check_category"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Check Category
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="check_category"
                    name="check_category"
                    required
                    class="{{ $inputClass }} @error('check_category') {{ $errorInputClass }} @enderror"
                >
                    <option value="">
                        Pilih kategori pemeriksaan
                    </option>

                    @foreach ($checkCategoryOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old('check_category', $dailyCheck?->check_category) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('check_category')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Product Name --}}
            <div class="md:col-span-2">
                <label
                    for="product_name"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Product Name
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="product_name"
                    type="text"
                    name="product_name"
                    value="{{ old('product_name', $dailyCheck?->product_name) }}"
                    required
                    maxlength="255"
                    placeholder="Contoh: Panel Distribusi K1"
                    class="{{ $inputClass }} @error('product_name') {{ $errorInputClass }} @enderror"
                >

                @error('product_name')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Batch Reference --}}
            <div>
                <label
                    for="batch_reference"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    TS / Batch / Car
                </label>

                <input
                    id="batch_reference"
                    type="text"
                    name="batch_reference"
                    value="{{ old('batch_reference', $dailyCheck?->batch_reference) }}"
                    maxlength="255"
                    placeholder="Masukkan nomor TS, batch, atau car"
                    class="{{ $inputClass }} @error('batch_reference') {{ $errorInputClass }} @enderror"
                >

                @error('batch_reference')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Inspector --}}
            <div>
                <label
                    for="inspector"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Inspector
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="inspector"
                    type="text"
                    name="inspector"
                    value="{{ old('inspector', $dailyCheck?->inspector) }}"
                    required
                    maxlength="255"
                    placeholder="Nama inspector"
                    class="{{ $inputClass }} @error('inspector') {{ $errorInputClass }} @enderror"
                >

                @error('inspector')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Cycle Time --}}
            <div>
                <label
                    for="cycle_time_minutes"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Cycle Time Check
                    <span class="text-xs font-normal text-gray-400">
                        (menit)
                    </span>
                </label>

                <input
                    id="cycle_time_minutes"
                    type="number"
                    name="cycle_time_minutes"
                    value="{{ old('cycle_time_minutes', $dailyCheck?->cycle_time_minutes) }}"
                    min="0"
                    step="0.01"
                    placeholder="0"
                    class="{{ $inputClass }} @error('cycle_time_minutes') {{ $errorInputClass }} @enderror"
                >

                @error('cycle_time_minutes')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- OIL Description --}}
            <div class="md:col-span-2 xl:col-span-3">
                <label
                    for="oil_description"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    OIL / Uraian Temuan
                </label>

                <textarea
                    id="oil_description"
                    name="oil_description"
                    rows="4"
                    placeholder="Masukkan uraian temuan atau informasi OIL"
                    class="{{ $inputClass }} resize-y @error('oil_description') {{ $errorInputClass }} @enderror"
                >{{ old('oil_description', $dailyCheck?->oil_description) }}</textarea>

                @error('oil_description')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Kategori Temuan --}}
    <section
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-start gap-4">
                <span
                    class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                        rounded-2xl bg-amber-50 text-amber-600
                        dark:bg-amber-500/15 dark:text-amber-300"
                >
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>

                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                        Kategori Temuan
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Masukkan jumlah temuan pada masing-masing kategori defect.
                    </p>
                </div>
            </div>

            <div
                class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-3
                    text-center dark:border-amber-900/40 dark:bg-amber-900/20"
            >
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">
                    Total Temuan
                </p>

                <p
                    class="mt-1 text-2xl font-black text-amber-700 dark:text-amber-300"
                    x-text="totalFindings"
                ></p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            @foreach ($quantityFields as $field => $item)
                @php
                    $alpineModel = match ($field) {
                        'visual_qty' => 'visual',
                        'skun_qty' => 'skun',
                        'cramping_qty' => 'cramping',
                        'marking_qty' => 'marking',
                        'belltest_qty' => 'belltest',
                        'function_qty' => 'functionQty',
                    };
                @endphp

                <div
                    class="rounded-2xl border border-gray-200 bg-gray-50 p-4
                        dark:border-gray-700 dark:bg-gray-800/70"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-xl
                                bg-white text-indigo-600 shadow-sm
                                dark:bg-gray-900 dark:text-indigo-300"
                        >
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                        </span>

                        <span
                            class="rounded-lg bg-gray-200 px-2 py-1 text-[10px]
                                font-black tracking-wider text-gray-600
                                dark:bg-gray-700 dark:text-gray-300"
                        >
                            {{ $item['short'] }}
                        </span>
                    </div>

                    <label
                        for="{{ $field }}"
                        class="mt-4 block text-sm font-bold text-gray-700 dark:text-gray-200"
                    >
                        {{ $item['label'] }}
                    </label>

                    <input
                        id="{{ $field }}"
                        type="number"
                        name="{{ $field }}"
                        value="{{ old($field, $dailyCheck?->{$field} ?? 0) }}"
                        min="0"
                        step="1"
                        inputmode="numeric"
                        x-model.number="{{ $alpineModel }}"
                        class="{{ $inputClass }} @error($field) {{ $errorInputClass }} @enderror"
                    >

                    @error($field)
                        <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endforeach
        </div>
    </section>

    {{-- Kuantitas OK dan NOK --}}
    <section
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-emerald-50 text-emerald-600
                    dark:bg-emerald-500/15 dark:text-emerald-300"
            >
                <i class="fa-solid fa-chart-column"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Kuantitas Hasil Pemeriksaan
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Isi jumlah hasil OK dan NOK untuk produk maupun kabel.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                'product_ok_qty' => ['Produk OK', 'emerald'],
                'product_nok_qty' => ['Produk NOK', 'red'],
                'cable_ok_qty' => ['Kabel OK', 'emerald'],
                'cable_nok_qty' => ['Kabel NOK', 'red'],
            ] as $field => [$label, $tone])
                <div>
                    <label
                        for="{{ $field }}"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                    >
                        {{ $label }}
                    </label>

                    <input
                        id="{{ $field }}"
                        type="number"
                        name="{{ $field }}"
                        value="{{ old($field, $dailyCheck?->{$field} ?? 0) }}"
                        min="0"
                        step="1"
                        inputmode="numeric"
                        class="{{ $inputClass }} @error($field) {{ $errorInputClass }} @enderror"
                    >

                    @error($field)
                        <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endforeach
        </div>
    </section>

    {{-- Penyelesaian --}}
    <section
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-cyan-50 text-cyan-600
                    dark:bg-cyan-500/15 dark:text-cyan-300"
            >
                <i class="fa-solid fa-circle-check"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Hasil dan Penyelesaian
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Status product dihitung otomatis berdasarkan Result.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            {{-- Result --}}
            <div>
                <label
                    for="result"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Result
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="result"
                    name="result"
                    required
                    x-model="result"
                    class="{{ $inputClass }} @error('result') {{ $errorInputClass }} @enderror"
                >
                    @foreach ($resultOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old('result', $dailyCheck?->result ?? 'pending') === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('result')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Calculated Status --}}
            <div>
                <span class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                    Status Product
                </span>

                <div
                    class="mt-2 flex h-[42px] items-center rounded-xl border px-4 text-sm font-bold"
                    :class="{
                        'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300': result === 'ok',
                        'border-red-200 bg-red-50 text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300': result === 'nok',
                        'border-gray-200 bg-gray-50 text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300': result === 'pending'
                    }"
                >
                    <i
                        class="fa-solid mr-2"
                        :class="{
                            'fa-circle-check': result === 'ok',
                            'fa-circle-xmark': result === 'nok',
                            'fa-clock': result === 'pending'
                        }"
                    ></i>

                    <span x-text="productStatus"></span>
                </div>
            </div>

            {{-- Closing Date --}}
            <div>
                <label
                    for="closing_oil_date"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Closing OIL Date
                </label>

                <input
                    id="closing_oil_date"
                    type="date"
                    name="closing_oil_date"
                    value="{{ old(
                        'closing_oil_date',
                        $dailyCheck?->closing_oil_date?->format('Y-m-d')
                    ) }}"
                    class="{{ $inputClass }} @error('closing_oil_date') {{ $errorInputClass }} @enderror"
                >

                @error('closing_oil_date')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Status IS --}}
            <div>
                <label
                    for="status_is"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Status IS
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="status_is"
                    name="status_is"
                    required
                    class="{{ $inputClass }} @error('status_is') {{ $errorInputClass }} @enderror"
                >
                    <option value="">
                        Pilih status IS
                    </option>

                    @foreach ($statusIsOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(old('status_is', $dailyCheck?->status_is) === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('status_is')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Oil Link --}}
            <div class="md:col-span-2">
                <label
                    for="oil_link"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Link OIL
                </label>

                <input
                    id="oil_link"
                    type="url"
                    name="oil_link"
                    value="{{ old('oil_link', $dailyCheck?->oil_link) }}"
                    maxlength="2048"
                    placeholder="https://..."
                    class="{{ $inputClass }} @error('oil_link') {{ $errorInputClass }} @enderror"
                >

                @error('oil_link')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Remarks --}}
            <div class="md:col-span-2 xl:col-span-3">
                <label
                    for="remarks"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Remarks
                </label>

                <textarea
                    id="remarks"
                    name="remarks"
                    rows="4"
                    placeholder="Tambahkan catatan pemeriksaan atau penyelesaian"
                    class="{{ $inputClass }} resize-y @error('remarks') {{ $errorInputClass }} @enderror"
                >{{ old('remarks', $dailyCheck?->remarks) }}</textarea>

                @error('remarks')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </section>

    {{-- NCR --}}
    <section
        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm
            dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-start gap-4">
            <span
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center
                    rounded-2xl bg-red-50 text-red-600
                    dark:bg-red-500/15 dark:text-red-300"
            >
                <i class="fa-solid fa-file-circle-exclamation"></i>
            </span>

            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Informasi NCR
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Bagian ini bersifat opsional dan diisi apabila pemeriksaan memiliki NCR.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2">
            <div>
                <label
                    for="ncr_number"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Nomor NCR
                </label>

                <input
                    id="ncr_number"
                    type="text"
                    name="ncr_number"
                    value="{{ old('ncr_number', $dailyCheck?->ncr_number) }}"
                    x-model="ncrNumber"
                    maxlength="255"
                    placeholder="Masukkan nomor NCR"
                    class="{{ $inputClass }} @error('ncr_number') {{ $errorInputClass }} @enderror"
                >

                @error('ncr_number')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label
                    for="ncr_category"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200"
                >
                    Kategori NCR
                    <span
                        x-show="ncrNumber.trim() !== ''"
                        class="text-red-500"
                    >*</span>
                </label>

                <select
                    id="ncr_category"
                    name="ncr_category"
                    :required="ncrNumber.trim() !== ''"
                    class="{{ $inputClass }} @error('ncr_category') {{ $errorInputClass }} @enderror"
                >
                    <option value="">
                        Pilih kategori NCR
                    </option>

                    @foreach ($ncrCategoryOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old('ncr_category', $dailyCheck?->ncr_category) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('ncr_category')
                    <p class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Action --}}
    <div
        class="sticky bottom-4 z-20 rounded-2xl border border-white/70
            bg-white/90 p-4 shadow-xl backdrop-blur
            dark:border-gray-800 dark:bg-gray-900/90"
    >
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a
                href="{{ $isEdit
                    ? route('monitoring-qc.product-electrical.daily-check.show', $dailyCheck)
                    : route('monitoring-qc.product-electrical.daily-check.index') }}"
                class="inline-flex items-center justify-center rounded-xl border
                    border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold
                    text-gray-700 shadow-sm transition hover:bg-gray-50
                    dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200
                    dark:hover:bg-gray-700"
            >
                <i class="fa-solid fa-xmark mr-2"></i>
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl
                    bg-gradient-to-r from-indigo-600 to-violet-600
                    px-6 py-2.5 text-sm font-bold text-white shadow-lg
                    shadow-indigo-500/25 transition
                    hover:-translate-y-0.5 hover:shadow-xl"
            >
                <i class="fa-solid fa-floppy-disk mr-2"></i>

                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Daily Check' }}
            </button>
        </div>
    </div>
</div>
