@php
    $record = $ncrDetail ?? null;

    $linkedDailyCheck =
        $selectedDailyCheck
        ?? $record?->dailyCheck;

    $inputClass = 'mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600
        bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200
        shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500';

    $dailyCheckMap = $dailyChecks
        ->mapWithKeys(function ($item) {
            return [
                (string) $item->id => [
                    'project_id' => $item->project_id,
                    'project_name' => $item->project_name,
                    'product_name' => $item->product_name,
                    'ncr_number' => $item->ncr_number,
                    'check_date' => $item->check_date?->format('Y-m-d'),
                ],
            ];
        })
        ->all();

    $initialDailyCheckId = (string) old(
        'daily_check_id',
        $record?->daily_check_id
            ?? $linkedDailyCheck?->id
            ?? ''
    );

    $initialProjectId = (string) old(
        'project_id',
        $record?->project_id
            ?? $linkedDailyCheck?->project_id
            ?? ''
    );

    $initialProductName = old(
        'product_name',
        $record?->product_name
            ?? $linkedDailyCheck?->product_name
            ?? ''
    );

    $initialNcrNumber = old(
        'ncr_number',
        $record?->ncr_number
            ?? $linkedDailyCheck?->ncr_number
            ?? ''
    );

    $initialIssuedDate = old(
        'issued_date',
        $record?->issued_date?->format('Y-m-d')
            ?? now()->format('Y-m-d')
    );

    $findingFields = [
        [
            'name' => 'visual_qty',
            'label' => 'Visual',
            'model' => 'visual',
        ],
        [
            'name' => 'completeness_qty',
            'label' => 'Kelengkapan',
            'model' => 'completeness',
        ],
        [
            'name' => 'specification_qty',
            'label' => 'Spesifikasi',
            'model' => 'specification',
        ],
        [
            'name' => 'dimension_qty',
            'label' => 'Dimensi',
            'model' => 'dimension',
        ],
        [
            'name' => 'function_qty',
            'label' => 'Fungsi',
            'model' => 'functionQty',
        ],
    ];
@endphp

<div
    x-data="{
        dailyChecks: @js($dailyCheckMap),

        dailyCheckId: @js($initialDailyCheckId),
        projectId: @js($initialProjectId),
        productName: @js($initialProductName),
        ncrNumber: @js($initialNcrNumber),

        visual: Number(@js(
            (int) old(
                'visual_qty',
                $record?->visual_qty ?? 0
            )
        )),

        completeness: Number(@js(
            (int) old(
                'completeness_qty',
                $record?->completeness_qty ?? 0
            )
        )),

        specification: Number(@js(
            (int) old(
                'specification_qty',
                $record?->specification_qty ?? 0
            )
        )),

        dimension: Number(@js(
            (int) old(
                'dimension_qty',
                $record?->dimension_qty ?? 0
            )
        )),

        functionQty: Number(@js(
            (int) old(
                'function_qty',
                $record?->function_qty ?? 0
            )
        )),

        get totalFindings() {
            return Number(this.visual || 0)
                + Number(this.completeness || 0)
                + Number(this.specification || 0)
                + Number(this.dimension || 0)
                + Number(this.functionQty || 0);
        },

        applyDailyCheck() {
            if (!this.dailyCheckId) {
                return;
            }

            const item =
                this.dailyChecks[
                    String(this.dailyCheckId)
                ];

            if (!item) {
                return;
            }

            if (item.project_id) {
                this.projectId =
                    String(item.project_id);
            }

            if (item.product_name) {
                this.productName =
                    item.product_name;
            }

            if (item.ncr_number) {
                this.ncrNumber =
                    item.ncr_number;
            }
        }
    }"
    class="space-y-6"
>
    {{-- Informasi NCR --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100
            bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="border-b border-gray-100 px-6 py-5
                dark:border-gray-700"
        >
            <h3
                class="text-base font-semibold text-gray-900
                    dark:text-gray-100"
            >
                Informasi NCR
            </h3>

            <p
                class="mt-1 text-sm text-gray-500
                    dark:text-gray-400"
            >
                Informasi utama NCR QC Final Elektrik.
            </p>
        </div>

        <div
            class="grid grid-cols-1 gap-5 p-6
                md:grid-cols-2 xl:grid-cols-3"
        >
            <div class="xl:col-span-3">
                <x-input-label
                    for="daily_check_id"
                    value="Referensi Daily Check"
                />

                <select
                    id="daily_check_id"
                    name="daily_check_id"
                    x-model="dailyCheckId"
                    @change="applyDailyCheck()"
                    class="{{ $inputClass }}"
                >
                    <option value="">
                        Tidak terhubung ke Daily Check
                    </option>

                    @foreach ($dailyChecks as $dailyCheck)
                        <option
                            value="{{ $dailyCheck->id }}"
                        >
                            {{ $dailyCheck->check_date?->format('d/m/Y') }}
                            —
                            {{ $dailyCheck->project_name }}
                            —
                            {{ $dailyCheck->product_name }}
                        </option>
                    @endforeach
                </select>

                <p
                    class="mt-1.5 text-xs text-gray-500
                        dark:text-gray-400"
                >
                    Opsional. Pilih jika NCR berasal dari salah satu
                    Daily Check QC Final Elektrik.
                </p>

                <x-input-error
                    :messages="$errors->get('daily_check_id')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="ncr_number"
                    value="Nomor NCR"
                />

                <input
                    id="ncr_number"
                    name="ncr_number"
                    type="text"
                    x-model="ncrNumber"
                    value="{{ $initialNcrNumber }}"
                    class="{{ $inputClass }}"
                    placeholder="Nomor NCR"
                    required
                >

                <x-input-error
                    :messages="$errors->get('ncr_number')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="issued_date"
                    value="Tanggal Terbit"
                />

                <x-text-input
                    id="issued_date"
                    name="issued_date"
                    type="date"
                    class="{{ $inputClass }}"
                    :value="$initialIssuedDate"
                    required
                />

                <x-input-error
                    :messages="$errors->get('issued_date')"
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
                        $record?->inspector
                            ?? auth()->user()->name
                            ?? ''
                    )"
                    placeholder="Nama inspector"
                />

                <x-input-error
                    :messages="$errors->get('inspector')"
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
                    x-model="projectId"
                    class="{{ $inputClass }}"
                >
                    <option value="">
                        Pilih project
                    </option>

                    @foreach ($projects as $project)
                        <option
                            value="{{ $project->id }}"
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

            <div class="md:col-span-2">
                <x-input-label
                    for="product_name"
                    value="Nama Produk / Proses"
                />

                <input
                    id="product_name"
                    name="product_name"
                    type="text"
                    x-model="productName"
                    value="{{ $initialProductName }}"
                    class="{{ $inputClass }}"
                    placeholder="Nama produk atau proses"
                    required
                >

                <x-input-error
                    :messages="$errors->get('product_name')"
                    class="mt-2"
                />
            </div>
        </div>
    </div>

    {{-- Detail Ketidaksesuaian --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100
            bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="border-b border-gray-100 px-6 py-5
                dark:border-gray-700"
        >
            <h3
                class="text-base font-semibold text-gray-900
                    dark:text-gray-100"
            >
                Detail Ketidaksesuaian
            </h3>

            <p
                class="mt-1 text-sm text-gray-500
                    dark:text-gray-400"
            >
                Lokasi, unit tujuan, dan uraian temuan.
            </p>
        </div>

        <div
            class="grid grid-cols-1 gap-5 p-6
                md:grid-cols-2"
        >
            <div>
                <x-input-label
                    for="nonconformity_location"
                    value="Lokasi Ketidaksesuaian"
                />

                <x-text-input
                    id="nonconformity_location"
                    name="nonconformity_location"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'nonconformity_location',
                        $record?->nonconformity_location ?? ''
                    )"
                    placeholder="Lokasi ketidaksesuaian"
                />

                <x-input-error
                    :messages="$errors->get('nonconformity_location')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="target_unit"
                    value="Unit Yang Dituju"
                />

                <x-text-input
                    id="target_unit"
                    name="target_unit"
                    type="text"
                    class="{{ $inputClass }}"
                    :value="old(
                        'target_unit',
                        $record?->target_unit ?? ''
                    )"
                    placeholder="Unit tujuan NCR"
                />

                <x-input-error
                    :messages="$errors->get('target_unit')"
                    class="mt-2"
                />
            </div>

            <div class="md:col-span-2">
                <x-input-label
                    for="nonconformity_description"
                    value="Uraian Ketidaksesuaian"
                />

                <textarea
                    id="nonconformity_description"
                    name="nonconformity_description"
                    rows="6"
                    required
                    class="{{ $inputClass }} resize-y"
                    placeholder="Jelaskan ketidaksesuaian yang ditemukan..."
                >{{ old(
                    'nonconformity_description',
                    $record?->nonconformity_description ?? ''
                ) }}</textarea>

                <x-input-error
                    :messages="$errors->get('nonconformity_description')"
                    class="mt-2"
                />
            </div>
        </div>
    </div>

    {{-- Kategori Defect --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100
            bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
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
                    Kategori Defect
                </h3>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Masukkan jumlah defect sesuai kategori NCR.
                </p>
            </div>

            <div
                class="rounded-2xl bg-orange-50 px-5 py-3
                    dark:bg-orange-900/20"
            >
                <p
                    class="text-xs font-medium uppercase tracking-wide
                        text-orange-600 dark:text-orange-400"
                >
                    Total Finding
                </p>

                <p
                    x-text="totalFindings"
                    class="mt-1 text-2xl font-bold text-orange-700
                        dark:text-orange-300"
                >
                    0
                </p>
            </div>
        </div>

        <div
            class="grid grid-cols-2 gap-4 p-6
                md:grid-cols-3 xl:grid-cols-5"
        >
            @foreach ($findingFields as $field)
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
                        x-model.number="{{ $field['model'] }}"
                        value="{{ old(
                            $field['name'],
                            $record?->{$field['name']} ?? 0
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

    {{-- Status --}}
    <div
        class="overflow-hidden rounded-3xl border border-gray-100
            bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
    >
        <div
            class="border-b border-gray-100 px-6 py-5
                dark:border-gray-700"
        >
            <h3
                class="text-base font-semibold text-gray-900
                    dark:text-gray-100"
            >
                Status NCR
            </h3>

            <p
                class="mt-1 text-sm text-gray-500
                    dark:text-gray-400"
            >
                Status NCR dihitung otomatis berdasarkan status komponen.
            </p>
        </div>

        <div
            class="grid grid-cols-1 gap-5 p-6
                md:grid-cols-2"
        >
            <div>
                <x-input-label
                    for="component_status"
                    value="Status Komponen"
                />

                <select
                    id="component_status"
                    name="component_status"
                    class="{{ $inputClass }}"
                    required
                >
                    @foreach ($componentStatusOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'component_status',
                                    $record?->component_status
                                        ?? 'pending'
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <x-input-error
                    :messages="$errors->get('component_status')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label
                    for="cycle_time_minutes"
                    value="Cycle Time Check (Minute)"
                />

                <x-text-input
                    id="cycle_time_minutes"
                    name="cycle_time_minutes"
                    type="number"
                    step="0.01"
                    min="0"
                    class="{{ $inputClass }}"
                    :value="old(
                        'cycle_time_minutes',
                        $record?->cycle_time_minutes ?? ''
                    )"
                    placeholder="Contoh: 45"
                />

                <x-input-error
                    :messages="$errors->get('cycle_time_minutes')"
                    class="mt-2"
                />
            </div>
        </div>
    </div>

    {{-- Action --}}
    <div
        class="sticky bottom-4 z-20 flex items-center justify-between gap-4
            rounded-2xl border border-gray-200 bg-white/95 px-5 py-4
            shadow-lg backdrop-blur dark:border-gray-700
            dark:bg-gray-800/95"
    >
        <a
            href="{{ route(
                'monitoring-qc.final-electrical.ncr.index'
            ) }}"
            class="inline-flex items-center justify-center rounded-xl
                border border-gray-300 px-4 py-2.5 text-sm font-medium
                text-gray-700 transition hover:bg-gray-50
                dark:border-gray-600 dark:text-gray-300
                dark:hover:bg-gray-700"
        >
            Batal
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-xl
                bg-indigo-600 px-5 py-2.5 text-sm font-semibold
                text-white shadow-sm transition hover:bg-indigo-700"
        >
            {{ $record
                ? 'Simpan Perubahan'
                : 'Simpan Detail NCR'
            }}
        </button>
    </div>
</div>
