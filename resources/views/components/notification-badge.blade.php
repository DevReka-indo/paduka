@props(['type'])

@php
    $typeClasses = match ($type) {
        'ncr_tanggapi' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 ring-1 ring-inset ring-amber-200 dark:ring-amber-800/40',
        'ncr_verifikasi' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 ring-1 ring-inset ring-blue-200 dark:ring-blue-800/40',
        'ncr_revisi' => 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400 ring-1 ring-inset ring-violet-200 dark:ring-violet-800/40',
        'ncr_terlambat' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 ring-1 ring-inset ring-rose-200 dark:ring-rose-800/40',
        default => 'bg-gray-50 dark:bg-gray-900/20 text-gray-600 dark:text-gray-400 ring-1 ring-inset ring-gray-200 dark:ring-gray-700',
    };

    $typeLabel = match ($type) {
        'ncr_tanggapi' => 'Perlu Ditanggapi',
        'ncr_verifikasi' => 'Perlu Diverifikasi',
        'ncr_revisi' => 'NCR Direvisi',
        'ncr_terlambat' => 'NCR Terlambat',
        default => str_replace('_', ' ', strtoupper($type)),
    };
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full {$typeClasses}"
]) }}>
    {{ $typeLabel }}
</span>
