<div class="flex items-center justify-between gap-4 px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 rounded-b-xl">
    @if($step > 1)
        <button type="submit" name="action" value="back" formnovalidate
                class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('ui.back') }}
        </button>
    @else
        <span></span>
    @endif

    <button type="submit" name="action" value="next"
            class="inline-flex items-center gap-2 bg-[#014DA4] hover:bg-[#013a7d] text-white font-semibold px-6 py-2.5 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#014DA4]/40 focus:ring-offset-2">
        {{ __('ui.continue') }}
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
</div>
