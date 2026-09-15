@php
    $model = $dailyCheck ?? null;
@endphp

<div class="space-y-6">
    {{-- Periode --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Periode Pelaporan
        </h3>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            Periode pelaporan digunakan untuk pengelompokan dashboard dan data historis.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div>
            <x-input-label
                for="reporting_year"
                value="Tahun Pelaporan"
            />

            <x-text-input
                id="reporting_year"
                name="reporting_year"
                type="number"
                min="2020"
                max="2100"
                class="mt-1 block w-full"
                :value="old(
                    'reporting_year',
                    $model?->reporting_year ?? now()->year
                )"
                required
            />

            <x-input-error
                :messages="$errors->get('reporting_year')"
                class="mt-2"
            />
        </div>

        <div>
            <x-input-label
                for="reporting_month"
                value="Bulan Pelaporan"
            />

            <select
                id="reporting_month"
                name="reporting_month"
                class="mt-1 block w-full rounded-lg border-slate-300
                    bg-white text-sm text-slate-900 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-100"
                required
            >
                @foreach ($monthOptions as $month => $label)
                    <option
                        value="{{ $month }}"
                        @selected(
                            (int) old(
                                'reporting_month',
                                $model?->reporting_month ?? now()->month
                            ) === (int) $month
                        )
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <x-input-error
                :messages="$errors->get('reporting_month')"
                class="mt-2"
            />
        </div>

        <div>
            <x-input-label
                for="check_date"
                value="Check Date"
            />

            <x-text-input
                id="check_date"
                name="check_date"
                type="date"
                class="mt-1 block w-full"
                :value="old(
                    'check_date',
                    $model?->check_date?->format('Y-m-d')
                        ?? now()->format('Y-m-d')
                )"
                required
            />

            <x-input-error
                :messages="$errors->get('check_date')"
                class="mt-2"
            />
        </div>
    </div>

    <div class="border-t border-slate-200 dark:border-slate-800"></div>

    {{-- Identitas Inspection --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Identitas Inspection
        </h3>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            Informasi project, dokumen dan proses inspeksi.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-input-label
                for="project_id"
                value="Project Master"
            />

            <select
                id="project_id"
                name="project_id"
                class="mt-1 block w-full rounded-lg border-slate-300
                    bg-white text-sm text-slate-900 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-100"
            >
                <option value="">
                    -- Pilih Project / Input Manual --
                </option>

                @foreach ($projects as $project)
                    <option
                        value="{{ $project->id }}"
                        @selected(
                            (string) old(
                                'project_id',
                                $model?->project_id
                            ) === (string) $project->id
                        )
                    >
                        {{ $project->kode_proyek }}
                        -
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
                for="project_name"
                value="Nama Project / Snapshot"
            />

            <x-text-input
                id="project_name"
                name="project_name"
                type="text"
                class="mt-1 block w-full"
                :value="old(
                    'project_name',
                    $model?->project_name
                )"
                placeholder="Contoh: Proyek 612"
            />

            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Digunakan jika project belum tersedia pada master project.
            </p>

            <x-input-error
                :messages="$errors->get('project_name')"
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
                class="mt-1 block w-full rounded-lg border-slate-300
                    bg-white text-sm text-slate-900 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-100"
            >
                <option value="">
                    -- Pilih --
                </option>

                @foreach ($documentCheckOptions as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(
                            old(
                                'document_check',
                                $model?->document_check
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
                class="mt-1 block w-full rounded-lg border-slate-300
                    bg-white text-sm text-slate-900 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-100"
            >
                <option value="">
                    -- Pilih Inspection Gate --
                </option>

                @foreach ($inspectionGateOptions as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(
                            old(
                                'inspection_gate',
                                $model?->inspection_gate
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
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-input-label
                for="final_assembly_product_name"
                value="Final Assy Product Name"
            />

            <x-text-input
                id="final_assembly_product_name"
                name="final_assembly_product_name"
                type="text"
                class="mt-1 block w-full"
                :value="old(
                    'final_assembly_product_name',
                    $model?->final_assembly_product_name
                )"
                required
            />

            <x-input-error
                :messages="$errors->get('final_assembly_product_name')"
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
                class="mt-1 block w-full"
                :value="old(
                    'batch_reference',
                    $model?->batch_reference
                )"
            />

            <x-input-error
                :messages="$errors->get('batch_reference')"
                class="mt-2"
            />
        </div>
    </div>

    <div>
        <x-input-label
            for="sub_part_assembly_name"
            value="Sub Part Assy Name"
        />

        <textarea
            id="sub_part_assembly_name"
            name="sub_part_assembly_name"
            rows="4"
            class="mt-1 block w-full rounded-lg border-slate-300
                bg-white text-sm text-slate-900 shadow-sm
                focus:border-indigo-500 focus:ring-indigo-500
                dark:border-slate-700 dark:bg-slate-900
                dark:text-slate-100"
        >{{ old(
            'sub_part_assembly_name',
            $model?->sub_part_assembly_name
        ) }}</textarea>

        <x-input-error
            :messages="$errors->get('sub_part_assembly_name')"
            class="mt-2"
        />
    </div>

    <div>
        <x-input-label
            for="oil_description"
            value="OIL / Temuan"
        />

        <textarea
            id="oil_description"
            name="oil_description"
            rows="4"
            class="mt-1 block w-full rounded-lg border-slate-300
                bg-white text-sm text-slate-900 shadow-sm
                focus:border-indigo-500 focus:ring-indigo-500
                dark:border-slate-700 dark:bg-slate-900
                dark:text-slate-100"
        >{{ old(
            'oil_description',
            $model?->oil_description
        ) }}</textarea>

        <x-input-error
            :messages="$errors->get('oil_description')"
            class="mt-2"
        />
    </div>

    <div class="border-t border-slate-200 dark:border-slate-800"></div>

    {{-- Metode Temuan --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Temuan Berdasarkan Metode Pengecekan
        </h3>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            Isi jumlah temuan untuk setiap kategori sesuai hasil inspeksi.
        </p>
    </div>

    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
        @foreach ($findingLabels as $field => $label)
            <div>
                <x-input-label
                    :for="$field"
                    :value="$label"
                />

                <x-text-input
                    :id="$field"
                    :name="$field"
                    type="number"
                    min="0"
                    class="mt-1 block w-full"
                    :value="old(
                        $field,
                        $model?->{$field} ?? 0
                    )"
                />

                <x-input-error
                    :messages="$errors->get($field)"
                    class="mt-2"
                />
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-input-label
                for="qty_ok"
                value="Qty - OK (pcs)"
            />

            <x-text-input
                id="qty_ok"
                name="qty_ok"
                type="number"
                min="0"
                class="mt-1 block w-full"
                :value="old(
                    'qty_ok',
                    $model?->qty_ok ?? 0
                )"
            />

            <x-input-error
                :messages="$errors->get('qty_ok')"
                class="mt-2"
            />
        </div>

        <div>
            <x-input-label
                for="qty_nok"
                value="Qty - NOK (pcs)"
            />

            <x-text-input
                id="qty_nok"
                name="qty_nok"
                type="number"
                min="0"
                class="mt-1 block w-full"
                :value="old(
                    'qty_nok',
                    $model?->qty_nok ?? 0
                )"
            />

            <x-input-error
                :messages="$errors->get('qty_nok')"
                class="mt-2"
            />
        </div>
    </div>

    <div class="border-t border-slate-200 dark:border-slate-800"></div>

    {{-- Closing --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Result & Closing
        </h3>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-input-label
                for="result"
                value="Result"
            />

            <select
                id="result"
                name="result"
                class="mt-1 block w-full rounded-lg border-slate-300
                    bg-white text-sm text-slate-900 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-100"
                required
            >
                @foreach ($resultOptions as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(
                            old(
                                'result',
                                $model?->result ?? 'pending'
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
                for="inspector"
                value="Inspector"
            />

            <x-text-input
                id="inspector"
                name="inspector"
                type="text"
                class="mt-1 block w-full"
                :value="old(
                    'inspector',
                    $model?->inspector
                )"
            />

            <x-input-error
                :messages="$errors->get('inspector')"
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
                class="mt-1 block w-full rounded-lg border-slate-300
                    bg-white text-sm text-slate-900 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-100"
            >
                <option value="">
                    -- Pilih Status IS --
                </option>

                @foreach ($statusIsOptions as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(
                            old(
                                'status_is',
                                $model?->status_is
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
                for="cycle_time_minutes"
                value="Cycle Time Check (minute)"
            />

            <x-text-input
                id="cycle_time_minutes"
                name="cycle_time_minutes"
                type="number"
                min="0"
                step="0.01"
                class="mt-1 block w-full"
                :value="old(
                    'cycle_time_minutes',
                    $model?->cycle_time_minutes
                )"
            />

            <x-input-error
                :messages="$errors->get('cycle_time_minutes')"
                class="mt-2"
            />
        </div>

        <div>
            <x-input-label
                for="closing_oil_date"
                value="Closing OIL Date"
            />

            <x-text-input
                id="closing_oil_date"
                name="closing_oil_date"
                type="date"
                class="mt-1 block w-full"
                :value="old(
                    'closing_oil_date',
                    $model?->closing_oil_date?->format('Y-m-d')
                )"
            />

            <x-input-error
                :messages="$errors->get('closing_oil_date')"
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
                class="mt-1 block w-full"
                :value="old(
                    'ncr_number',
                    $model?->ncr_number
                )"
            />

            <x-input-error
                :messages="$errors->get('ncr_number')"
                class="mt-2"
            />
        </div>
    </div>

    <div>
        <x-input-label
            for="remarks"
            value="Remarks"
        />

        <textarea
            id="remarks"
            name="remarks"
            rows="4"
            class="mt-1 block w-full rounded-lg border-slate-300
                bg-white text-sm text-slate-900 shadow-sm
                focus:border-indigo-500 focus:ring-indigo-500
                dark:border-slate-700 dark:bg-slate-900
                dark:text-slate-100"
        >{{ old(
            'remarks',
            $model?->remarks
        ) }}</textarea>

        <x-input-error
            :messages="$errors->get('remarks')"
            class="mt-2"
        />
    </div>
</div>
