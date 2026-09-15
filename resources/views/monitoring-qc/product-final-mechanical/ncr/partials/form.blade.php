@php
    $model = $ncr ?? null;

    $currentDailyCheckId = old(
        'daily_check_id',
        $model?->daily_check_id
            ?? $selectedDailyCheck?->id
    );

    $defaultReportingYear = old(
        'reporting_year',
        $model?->reporting_year
            ?? $selectedDailyCheck?->reporting_year
            ?? now()->year
    );

    $defaultReportingMonth = old(
        'reporting_month',
        $model?->reporting_month
            ?? $selectedDailyCheck?->reporting_month
            ?? now()->month
    );

    $defaultProjectId = old(
        'project_id',
        $model?->project_id
            ?? $selectedDailyCheck?->project_id
    );

    $defaultProjectName = old(
        'project_name',
        $model?->project_name
            ?? $selectedDailyCheck?->project_name
    );

    $defaultProductName = old(
        'product_name',
        $model?->product_name
            ?? $selectedDailyCheck?->final_assembly_product_name
    );

    $defaultNcrNumber = old(
        'ncr_number',
        $model?->ncr_number
            ?? $selectedDailyCheck?->ncr_number
    );
@endphp

<div class="space-y-6">
    {{-- Relasi Daily Check --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Referensi Daily Check
        </h3>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            Relasi ini opsional. Detail NCR historis dapat berdiri sendiri tanpa Daily Check.
        </p>
    </div>

    <div>
        <x-input-label
            for="daily_check_id"
            value="Daily Check Terkait"
        />

        <select
            id="daily_check_id"
            name="daily_check_id"
            class="mt-1 block w-full rounded-lg border-slate-300
                bg-white text-sm text-slate-900 shadow-sm
                focus:border-indigo-500 focus:ring-indigo-500
                dark:border-slate-700 dark:bg-slate-900
                dark:text-slate-100"
        >
            <option value="">
                -- Tanpa Relasi Daily Check --
            </option>

            @foreach ($dailyChecks as $dailyCheck)
                <option
                    value="{{ $dailyCheck->id }}"
                    @selected(
                        (string) $currentDailyCheckId
                        === (string) $dailyCheck->id
                    )
                >
                    {{ $dailyCheck->check_date?->format('d/m/Y') }}
                    |
                    {{ $dailyCheck->project_name ?? '-' }}
                    |
                    {{ $dailyCheck->final_assembly_product_name }}
                    @if ($dailyCheck->ncr_number)
                        | NCR {{ $dailyCheck->ncr_number }}
                    @endif
                </option>
            @endforeach
        </select>

        <x-input-error
            :messages="$errors->get('daily_check_id')"
            class="mt-2"
        />
    </div>

    <div class="border-t border-slate-200 dark:border-slate-800"></div>

    {{-- Periode --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Periode Pelaporan
        </h3>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            Periode pelaporan mengikuti periode monitoring, tidak harus sama dengan bulan tanggal terbit NCR.
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
                :value="$defaultReportingYear"
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
                            (int) $defaultReportingMonth
                            === (int) $month
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
                for="issued_date"
                value="Tanggal Terbit NCR"
            />

            <x-text-input
                id="issued_date"
                name="issued_date"
                type="date"
                class="mt-1 block w-full"
                :value="old(
                    'issued_date',
                    $model?->issued_date?->format('Y-m-d')
                        ?? now()->format('Y-m-d')
                )"
                required
            />

            <x-input-error
                :messages="$errors->get('issued_date')"
                class="mt-2"
            />
        </div>
    </div>

    <div class="border-t border-slate-200 dark:border-slate-800"></div>

    {{-- Identitas NCR --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Identitas NCR
        </h3>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-input-label
                for="ncr_number"
                value="Nomor NCR"
            />

            <x-text-input
                id="ncr_number"
                name="ncr_number"
                type="text"
                class="mt-1 block w-full"
                :value="$defaultNcrNumber"
                placeholder="Contoh: 0589"
                required
            />

            <x-input-error
                :messages="$errors->get('ncr_number')"
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
                        ?? $selectedDailyCheck?->inspector
                )"
            />

            <x-input-error
                :messages="$errors->get('inspector')"
                class="mt-2"
            />
        </div>

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
                            (string) $defaultProjectId
                            === (string) $project->id
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
                :value="$defaultProjectName"
                placeholder="Digunakan jika project belum ada di master"
            />

            <x-input-error
                :messages="$errors->get('project_name')"
                class="mt-2"
            />
        </div>
    </div>

    <div>
        <x-input-label
            for="product_name"
            value="Nama Produk / Proses"
        />

        <x-text-input
            id="product_name"
            name="product_name"
            type="text"
            class="mt-1 block w-full"
            :value="$defaultProductName"
            required
        />

        <x-input-error
            :messages="$errors->get('product_name')"
            class="mt-2"
        />
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-input-label
                for="nonconformity_location"
                value="Lokasi Ketidaksesuaian"
            />

            <x-text-input
                id="nonconformity_location"
                name="nonconformity_location"
                type="text"
                class="mt-1 block w-full"
                :value="old(
                    'nonconformity_location',
                    $model?->nonconformity_location
                )"
                placeholder="Contoh: PT. Rekaindo WS. Sukosari - Internal"
            />

            <x-input-error
                :messages="$errors->get('nonconformity_location')"
                class="mt-2"
            />
        </div>

        <div>
            <x-input-label
                for="target_unit"
                value="Unit yang Dituju"
            />

            <x-text-input
                id="target_unit"
                name="target_unit"
                type="text"
                class="mt-1 block w-full"
                :value="old(
                    'target_unit',
                    $model?->target_unit
                )"
                placeholder="Contoh: UNIT PRODUKSI WORKSHOP SUKOSARI"
            />

            <x-input-error
                :messages="$errors->get('target_unit')"
                class="mt-2"
            />
        </div>
    </div>

    <div>
        <x-input-label
            for="nonconformity_description"
            value="Uraian Ketidaksesuaian"
        />

        <textarea
            id="nonconformity_description"
            name="nonconformity_description"
            rows="5"
            class="mt-1 block w-full rounded-lg border-slate-300
                bg-white text-sm text-slate-900 shadow-sm
                focus:border-indigo-500 focus:ring-indigo-500
                dark:border-slate-700 dark:bg-slate-900
                dark:text-slate-100"
            required
        >{{ old(
            'nonconformity_description',
            $model?->nonconformity_description
        ) }}</textarea>

        <x-input-error
            :messages="$errors->get('nonconformity_description')"
            class="mt-2"
        />
    </div>

    <div class="border-t border-slate-200 dark:border-slate-800"></div>

    {{-- Kategori Defect --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Kategori Defect
        </h3>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            Detail NCR Mekanik menggunakan kategori Visual, Dimensi, dan Fungsi.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
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

    <div class="border-t border-slate-200 dark:border-slate-800"></div>

    {{-- Status --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
            Status NCR
        </h3>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <x-input-label
                for="component_status"
                value="Status Komponen"
            />

            <select
                id="component_status"
                name="component_status"
                class="mt-1 block w-full rounded-lg border-slate-300
                    bg-white text-sm text-slate-900 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:border-slate-700 dark:bg-slate-900
                    dark:text-slate-100"
                required
            >
                @foreach ($componentStatusOptions as $value => $label)
                    <option
                        value="{{ $value }}"
                        @selected(
                            old(
                                'component_status',
                                $model?->component_status ?? 'pending'
                            ) === $value
                        )
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                OK menghasilkan status NCR Close, NOK menghasilkan Open.
            </p>

            <x-input-error
                :messages="$errors->get('component_status')"
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
    </div>
</div>
