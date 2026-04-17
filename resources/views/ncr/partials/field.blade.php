<div>
    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">{{ $label }}</p>
    @if(!empty($value))
        @if(isset($multiline) && $multiline)
            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $value }}</p>
        @elseif(isset($badge) && $badge)
            <span class="inline-flex items-center text-xs font-semibold {{ $badgeClass ?? 'bg-gray-100 text-gray-600' }} px-2.5 py-1 rounded-full">
                {{ $value }}
            </span>
        @else
            <p class="text-sm text-gray-700">{{ $value }}</p>
        @endif
    @else
        <p class="text-sm text-gray-400 italic">—</p>
    @endif
</div>
