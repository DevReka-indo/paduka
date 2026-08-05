@props([
    'label',
    'name',
    'options' => collect(),
])

@php
    $selectedValues = collect(
        (array) request("column_filters.$name", [])
    )
        ->map(fn ($value) => (string) $value)
        ->values()
        ->all();

    $filterActive = count($selectedValues) > 0;
@endphp

<div
    class="relative"
    x-data="{
        opened: false,
        search: '',
        menuTop: 0,
        menuLeft: 0,
        menuMaxHeight: 440,

        selected: @js($selectedValues),

        options: @js(
            collect($options)
                ->map(fn ($value) => [
                    'value' => (string) $value,
                    'label' => (string) $value,
                ])
                ->values()
        ),

        get filteredOptions() {
            const keyword = this.search
                .trim()
                .toLowerCase();

            return this.options.filter(option =>
                option.label
                    .toLowerCase()
                    .includes(keyword)
            );
        },

        toggleMenu() {
            this.opened = !this.opened;

            if (this.opened) {
                this.$nextTick(() => {
                    this.positionMenu();
                });
            }
        },

        positionMenu() {
            const trigger = this.$refs.trigger;

            if (!trigger) {
                return;
            }

            const rect = trigger.getBoundingClientRect();
            const viewportPadding = 8;
            const dropdownGap = 8;
            const menuWidth = 288;
            const desiredHeight = 440;

            /*
             * Posisi horizontal.
             * Dropdown digeser ke kiri apabila melebihi sisi kanan layar.
             */
            this.menuLeft = Math.min(
                Math.max(
                    viewportPadding,
                    rect.left
                ),
                window.innerWidth
                    - menuWidth
                    - viewportPadding
            );

            const availableBelow =
                window.innerHeight
                - rect.bottom
                - dropdownGap
                - viewportPadding;

            const availableAbove =
                rect.top
                - dropdownGap
                - viewportPadding;

            /*
             * Buka ke bawah apabila ruangnya mencukupi.
             */
            if (
                availableBelow >= 300
                || availableBelow >= availableAbove
            ) {
                this.menuTop =
                    rect.bottom
                    + dropdownGap;

                this.menuMaxHeight = Math.min(
                    desiredHeight,
                    availableBelow
                );

                return;
            }

            /*
             * Jika bagian bawah tidak cukup, buka ke atas.
             */
            this.menuMaxHeight = Math.min(
                desiredHeight,
                availableAbove
            );

            this.menuTop = Math.max(
                viewportPadding,
                rect.top
                    - this.menuMaxHeight
                    - dropdownGap
            );
        },

        applyFilter() {
            const url = new URL(window.location.href);
            const key = 'column_filters[{{ $name }}][]';

            url.searchParams.delete(key);
            url.searchParams.delete('page');

            this.selected.forEach(value => {
                url.searchParams.append(
                    key,
                    value
                );
            });

            window.location.href = url.toString();
        },

        clearFilter() {
            const url = new URL(window.location.href);
            const key = 'column_filters[{{ $name }}][]';

            url.searchParams.delete(key);
            url.searchParams.delete('page');

            window.location.href = url.toString();
        },

        applySort(direction) {
            const url = new URL(window.location.href);

            url.searchParams.set(
                'sort',
                '{{ $name }}'
            );

            url.searchParams.set(
                'direction',
                direction
            );

            url.searchParams.delete('page');

            window.location.href = url.toString();
        },

        selectAllVisible() {
            this.selected = [
                ...new Set([
                    ...this.selected,
                    ...this.filteredOptions.map(
                        option => option.value
                    )
                ])
            ];
        },

        clearSelection() {
            this.selected = [];
        },

        closeMenu() {
            this.opened = false;
            this.search = '';
        }
    }"
    @resize.window="
        if (opened) {
            positionMenu();
        }
    "
    @scroll.window="
        if (opened) {
            positionMenu();
        }
    "
    @keydown.escape.window="closeMenu()"
>
    {{-- Nama kolom dan tombol filter --}}
    <div class="flex items-center gap-1.5 whitespace-nowrap">
        <span>{{ $label }}</span>

        <button
            x-ref="trigger"
            type="button"
            @click.stop="toggleMenu()"
            class="inline-flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-700
                {{ $filterActive
                    ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300'
                    : '' }}"
            title="Filter {{ $label }}"
        >
            <i class="fa-solid fa-filter text-[10px]"></i>
        </button>
    </div>

    {{--
        Dropdown dipindahkan ke body agar tidak terpotong oleh
        overflow-hidden atau overflow-x-auto pada tabel.
    --}}
    <template x-teleport="body">
        <div
            x-cloak
            x-show="opened"
            x-transition.opacity.duration.150ms
            @click.outside="closeMenu()"
            :style="{
                top: menuTop + 'px',
                left: menuLeft + 'px',
                maxHeight: menuMaxHeight + 'px'
            }"
            class="fixed z-[9999] w-72 overflow-y-auto rounded-xl border border-gray-200 bg-white p-3 text-left normal-case tracking-normal shadow-2xl dark:border-gray-700 dark:bg-gray-900"
        >
            {{-- Sorting --}}
            <div class="space-y-1 border-b border-gray-100 pb-3 dark:border-gray-800">
                <button
                    type="button"
                    @click="applySort('asc')"
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800"
                >
                    <i class="fa-solid fa-arrow-down-a-z w-4"></i>
                    Urutkan naik
                </button>

                <button
                    type="button"
                    @click="applySort('desc')"
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800"
                >
                    <i class="fa-solid fa-arrow-up-z-a w-4"></i>
                    Urutkan turun
                </button>
            </div>

            {{-- Search --}}
            <div class="py-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>

                    <input
                        type="text"
                        x-model="search"
                        placeholder="Cari data..."
                        class="w-full rounded-lg border border-gray-200 py-2 pl-9 pr-3 text-sm font-normal text-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    >
                </div>
            </div>

            {{-- Select Actions --}}
            <div class="mb-2 flex items-center justify-between text-xs">
                <button
                    type="button"
                    @click="selectAllVisible()"
                    class="font-semibold text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                >
                    Pilih semua
                </button>

                <button
                    type="button"
                    @click="clearSelection()"
                    class="font-semibold text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    Kosongkan
                </button>
            </div>

            {{-- Options --}}
            <div class="max-h-56 space-y-1 overflow-y-auto">
                <template
                    x-for="option in filteredOptions"
                    :key="option.value"
                >
                    <label class="flex cursor-pointer items-start gap-2 rounded-lg px-2 py-1.5 transition hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input
                            type="checkbox"
                            :value="option.value"
                            x-model="selected"
                            class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800"
                        >

                        <span
                            x-text="option.label"
                            class="break-words text-sm font-normal text-gray-700 dark:text-gray-200"
                        ></span>
                    </label>
                </template>

                <p
                    x-show="filteredOptions.length === 0"
                    class="py-4 text-center text-xs font-normal text-gray-400"
                >
                    Data tidak ditemukan.
                </p>
            </div>

            {{-- Footer Actions --}}
            <div class="mt-3 flex items-center justify-between gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
                <button
                    type="button"
                    @click="clearFilter()"
                    class="text-xs font-semibold text-red-600 transition hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                >
                    Hapus filter
                </button>

                <div class="flex gap-2">
                    <button
                        type="button"
                        @click="closeMenu()"
                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        @click="applyFilter()"
                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700"
                    >
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
