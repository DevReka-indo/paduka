<div>
    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1.5">
        {{ $label }}
    </p>

    @if(!empty($value))
        @if(isset($multiline) && $multiline)
            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">
                {{ $value }}
            </p>
        @elseif(isset($badge) && $badge)
            <span class="inline-flex items-center text-xs font-semibold {{ $badgeClass ?? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }} px-2.5 py-1 rounded-full">
                {{ $value }}
            </span>
        @else
            <p class="text-sm text-gray-700 dark:text-gray-300">
                {{ $value }}
            </p>
        @endif
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">—</p>
    @endif
</div>
