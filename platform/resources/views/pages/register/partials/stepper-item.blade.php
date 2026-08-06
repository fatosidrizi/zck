<span class="shrink-0 mt-0.5 w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold
    {{ $isCurrent ? 'bg-[#014DA4] text-white ring-4 ring-[#014DA4]/15' : ($isDone ? 'bg-[#014DA4] text-white' : 'bg-gray-200 text-gray-500') }}">
    @if($isDone && ! $isCurrent)
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="sr-only">{{ __('ui.step_completed') }}</span>
    @else
        {{ $number }}
    @endif
</span>
<span class="min-w-0">
    <span class="block text-sm font-semibold {{ $isCurrent ? 'text-[#014DA4]' : 'text-gray-900' }}">{{ $meta['label'] }}</span>
    <span class="block text-xs text-gray-500 leading-snug mt-0.5">{{ $meta['desc'] }}</span>
</span>
