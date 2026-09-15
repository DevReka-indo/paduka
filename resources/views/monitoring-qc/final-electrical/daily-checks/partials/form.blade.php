@php
    $isEdit = isset($dailyCheck);

    $inputClass = 'mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600
        bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200
        shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500';

    $readonlyClass = 'mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-700
        bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400
        shadow-sm text-sm cursor-not-allowed';

    $quantityFields = [
        [
            'name' => 'visual_qty',
            'label' => 'Visual',
        ],
        [
            'name' => 'completeness_qty',
            'label' => 'Kelengkapan',
        ],
        [
            'name' => 'belltest_qty',
            'label' => 'Belltest',
        ],
        [
            'name' => 'function_qty',
            'label' => 'Fungsi',
        ],
        [
            'name' => 'torque_qty',
            'label' => 'Torsi',
        ],
    ];
@endphp

<div
    x-data="{
        visual: Number(@js((int) old('visual_qty', $dailyCheck->visual_qty ?? 0))),
        completeness: Number(@js((int) old('completeness_qty', $dailyCheck->completeness_qty ?? 0))),
        belltest: Number(@js((int) old('belltest_qty', $dailyCheck->belltest_qty ?? 0))),
        functionQty: Number(@js((int) old('function_qty', $dailyCheck->function_qty ?? 0))),
        torque: Number(@js((int) old('torque_qty', $dailyCheck->torque_qty ?? 0))),

        get totalFindings() {
            return Number(this.visual || 0)
                + Number(this.completeness || 0)
                + Number(this.belltest || 0)
                + Number(this.functionQty || 0)
                + Number(this.torque || 0);
        }
    }"
    class="space-y-6"
>
    {{-- Informasi Pemeriksaan --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm
            dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="border-b border-gray-100 px-6 py-5
                dark:border-gray-700"
        >
            <h3
                class="text-base font-semibold text-gray-900
                    dark:text-gray-100"
            >
                Informasi Pemeriksaan
            </h3>

            <p
                class="mt-1 text-sm text-gray-500
                    dark:text-gray-400"
            >
                Informasi utama Final Inspection Elektrik.
            </p>
        </div>

        <div
            class="grid grid-cols-1 gap-5 p-6
                md:grid-cols-2 xl:grid-cols-3"
        >
            <div>
                <x-input-label
                    for="check_date"
                    value="Check Date"
                />

                <x-text-input
                    id="check_date"
                    name="check_date"
                    type="date"
                    class="{{ $inputClass }}"
                    :value="old(
                        'check_date',
                        isset($dailyCheck)
                            ? $dailyCheck->check_date?->format('Y-m-d')
                            : now()->format('Y-m-d')
                    )"
                    required
                />

                <x-input-error
                    :messages="$errors->get('check_date')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="project_id"
                    value="Project"
                />

                <select
                    id="project_id"
                    name="project_id"
                    class="{{ $inputClass }}"
                    required
                >
                    <option value="">
                        Pilih proyek
                    </option>

                    @foreach ($projects as $project)
                        <option
                            value="{{ $project->id }}"
                            @selected(
                                old(
                                    'project_id',
                                    $dailyCheck->project_id ?? ''
                                ) == $project->id
                            )
                        >
                            {{ $project->kode_proyek }}
                            —
                            {{ $project->nama_proyek }}
                        </option>
                    @endforeach
                </select>

                <x-input-error
                    :messages="$errors->get('project_id')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="document_check"
                    value="Doc. Check"
                />

                <select
                    id="document_check"
                    name="document_check"
                    class="{{ $inputClass }}"
                    required
                >
                    <option value="">
                        Pilih dokumen
                    </option>

                    @foreach ($documentCheckOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'document_check',
                                    $dailyCheck->document_check ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <x-input-error
                    :messages="$errors->get('document_check')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="inspection_gate"
                    value="Inspection Gate"
                />

                <select
                    id="inspection_gate"
                    name="inspection_gate"
                    class="{{ $inputClass }}"
                    required
                >
                    <option value="">
                        Pilih lokasi pemeriksaan
                    </option>

                    @foreach ($inspectionGateOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'inspection_gate',
                                    $dailyCheck->inspection_gate ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <x-input-error
                    :messages="$errors->get('inspection_gate')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="product_name"
                    value="Product Name"
                />

                <x-text-input
                    id="product_name"
                    name="product_name"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'product_name',
                        $dailyCheck->product_name ?? ''
                    )"
                    placeholder="Nama produk"
                    required
                />

                <x-input-error
                    :messages="$errors->get('product_name')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="check_category"
                    value="Check Category"
                />

                <select
                    id="check_category"
                    name="check_category"
                    class="{{ $inputClass }}"
                    required
                >
                    <option value="">
                        Pilih kategori
                    </option>

                    @foreach ($checkCategoryOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'check_category',
                                    $dailyCheck->check_category ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <x-input-error
                    :messages="$errors->get('check_category')"
                    class="mt-2"
                />
            </div>
        </div>
    </div>

    {{-- Identitas Unit --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm
            dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="border-b border-gray-100 px-6 py-5
                dark:border-gray-700"
        >
            <h3
                class="text-base font-semibold text-gray-900
                    dark:text-gray-100"
            >
                Identitas Unit
            </h3>

            <p
                class="mt-1 text-sm text-gray-500
                    dark:text-gray-400"
            >
                Nomor seri, car, dan referensi TS / batch.
            </p>
        </div>

        <div
            class="grid grid-cols-1 gap-5 p-6
                md:grid-cols-3"
        >
            <div>
                <x-input-label
                    for="serial_number"
                    value="SN"
                />

                <x-text-input
                    id="serial_number"
                    name="serial_number"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'serial_number',
                        $dailyCheck->serial_number ?? ''
                    )"
                    placeholder="Serial number"
                />

                <x-input-error
                    :messages="$errors->get('serial_number')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="car_reference"
                    value="Car"
                />

                <x-text-input
                    id="car_reference"
                    name="car_reference"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'car_reference',
                        $dailyCheck->car_reference ?? ''
                    )"
                    placeholder="Car"
                />

                <x-input-error
                    :messages="$errors->get('car_reference')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="batch_reference"
                    value="TS / Batch"
                />

                <x-text-input
                    id="batch_reference"
                    name="batch_reference"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'batch_reference',
                        $dailyCheck->batch_reference ?? ''
                    )"
                    placeholder="TS / Batch"
                />

                <x-input-error
                    :messages="$errors->get('batch_reference')"
                    class="mt-2"
                />
            </div>
        </div>
    </div>

    {{-- Temuan --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm
            dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5
                dark:border-gray-700
                sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <h3
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Jenis Temuan
                </h3>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Masukkan jumlah temuan pada masing-masing kategori.
                </p>
            </div>

            <div
                class="rounded-2xl bg-orange-50 px-5 py-3
                    dark:bg-orange-900/20"
            >
                <p
                    class="text-xs font-medium uppercase tracking-wide text-orange-600
                        dark:text-orange-400"
                >
                    Total Finding
                </p>

                <p
                    class="mt-1 text-2xl font-bold text-orange-700
                        dark:text-orange-300"
                    x-text="totalFindings"
                >
                    0
                </p>
            </div>
        </div>

        <div
            class="grid grid-cols-2 gap-4 p-6
                md:grid-cols-3 xl:grid-cols-5"
        >
            @foreach ($quantityFields as $field)
                @php
                    $xModel = match ($field['name']) {
                        'visual_qty' => 'visual',
                        'completeness_qty' => 'completeness',
                        'belltest_qty' => 'belltest',
                        'function_qty' => 'functionQty',
                        'torque_qty' => 'torque',
                    };
                @endphp

                <div>
                    <x-input-label
                        :for="$field['name']"
                        :value="$field['label']"
                    />

                    <input
                        id="{{ $field['name'] }}"
                        name="{{ $field['name'] }}"
                        type="number"
                        min="0"
                        step="1"
                        x-model.number="{{ $xModel }}"
                        value="{{ old(
                            $field['name'],
                            $dailyCheck->{$field['name']} ?? 0
                        ) }}"
                        class="{{ $inputClass }}"
                    >

                    <x-input-error
                        :messages="$errors->get($field['name'])"
                        class="mt-2"
                    />
                </div>
            @endforeach
        </div>
    </div>

    {{-- OIL --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm
            dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="border-b border-gray-100 px-6 py-5
                dark:border-gray-700"
        >
            <h3
                class="text-base font-semibold text-gray-900
                    dark:text-gray-100"
            >
                OIL
            </h3>

            <p
                class="mt-1 text-sm text-gray-500
                    dark:text-gray-400"
            >
                Detail Outstanding Inspection List dan penyelesaiannya.
            </p>
        </div>

        <div
            class="grid grid-cols-1 gap-5 p-6
                md:grid-cols-2"
        >
            <div class="md:col-span-2">
                <x-input-label
                    for="oil_description"
                    value="OIL"
                />

                <textarea
                    id="oil_description"
                    name="oil_description"
                    rows="4"
                    class="{{ $inputClass }}"
                    placeholder="Detail OIL apabila ada..."
                >{{ old(
                    'oil_description',
                    $dailyCheck->oil_description ?? ''
                ) }}</textarea>

                <x-input-error
                    :messages="$errors->get('oil_description')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="oil_count"
                    value="Jumlah OIL"
                />

                <input
                    id="oil_count"
                    name="oil_count"
                    type="number"
                    min="0"
                    step="1"
                    value="{{ old(
                        'oil_count',
                        $dailyCheck->oil_count ?? 0
                    ) }}"
                    class="{{ $inputClass }}"
                >

                <x-input-error
                    :messages="$errors->get('oil_count')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="closing_oil_date"
                    value="Closing OIL / Date"
                />

                <x-text-input
                    id="closing_oil_date"
                    name="closing_oil_date"
                    type="date"
                    class="{{ $inputClass }}"
                    :value="old(
                        'closing_oil_date',
                        isset($dailyCheck)
                            ? $dailyCheck->closing_oil_date?->format('Y-m-d')
                            : ''
                    )"
                />

                <x-input-error
                    :messages="$errors->get('closing_oil_date')"
                    class="mt-2"
                />
            </div>

            <div class="md:col-span-2">
                <x-input-label
                    for="oil_link"
                    value="Link OIL"
                />

                <x-text-input
                    id="oil_link"
                    name="oil_link"
                    type="url"
                    class="{{ $inputClass }}"
                    :value="old(
                        'oil_link',
                        $dailyCheck->oil_link ?? ''
                    )"
                    placeholder="https://..."
                />

                <x-input-error
                    :messages="$errors->get('oil_link')"
                    class="mt-2"
                />
            </div>
        </div>
    </div>

    {{-- Result --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm
            dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="border-b border-gray-100 px-6 py-5
                dark:border-gray-700"
        >
            <h3
                class="text-base font-semibold text-gray-900
                    dark:text-gray-100"
            >
                Hasil Pemeriksaan
            </h3>
        </div>

        <div
            class="grid grid-cols-1 gap-5 p-6
                md:grid-cols-2 xl:grid-cols-4"
        >
            <div>
                <x-input-label
                    for="result"
                    value="Result"
                />

                <select
                    id="result"
                    name="result"
                    class="{{ $inputClass }}"
                    required
                >
                    @foreach ($resultOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'result',
                                    $dailyCheck->result ?? 'pending'
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <x-input-error
                    :messages="$errors->get('result')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="status_is"
                    value="Status IS"
                />

                <select
                    id="status_is"
                    name="status_is"
                    class="{{ $inputClass }}"
                >
                    <option value="">
                        Pilih status
                    </option>

                    @foreach ($statusIsOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'status_is',
                                    $dailyCheck->status_is ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <x-input-error
                    :messages="$errors->get('status_is')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="inspector"
                    value="Inspector"
                />

                <x-text-input
                    id="inspector"
                    name="inspector"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'inspector',
                        $dailyCheck->inspector
                            ?? auth()->user()->name
                            ?? ''
                    )"
                    required
                />

                <x-input-error
                    :messages="$errors->get('inspector')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="ncr_number"
                    value="No. NCR"
                />

                <x-text-input
                    id="ncr_number"
                    name="ncr_number"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'ncr_number',
                        $dailyCheck->ncr_number ?? ''
                    )"
                    placeholder="Nomor NCR apabila ada"
                />

                <x-input-error
                    :messages="$errors->get('ncr_number')"
                    class="mt-2"
                />
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div
        class="sticky bottom-4 z-20 flex items-center justify-between gap-4
            rounded-2xl border border-gray-200 bg-white/95 px-5 py-4 shadow-lg
            backdrop-blur dark:border-gray-700 dark:bg-gray-800/95"
    >
        <a
            href="{{ route(
                'monitoring-qc.final-electrical.daily-check.index'
            ) }}"
            class="inline-flex items-center justify-center rounded-xl border
                border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700
                transition hover:bg-gray-50
                dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
        >
            Batal
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-xl
                bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white
                shadow-sm transition hover:bg-indigo-700
                focus:outline-none focus:ring-2 focus:ring-indigo-500
                focus:ring-offset-2 dark:ring-offset-gray-900"
        >
            {{ $isEdit
                ? 'Simpan Perubahan'
                : 'Simpan Daily Check'
            }}
        </button>
    </div>
</div>
