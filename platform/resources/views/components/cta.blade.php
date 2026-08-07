{{--
    The platform's two primary calls to action, defined once.

    Hierarchy is fixed regardless of surface: "report" is the primary action
    (filled), "register" is secondary (outlined). Only the palette flips so the
    filled button stays the highest-contrast element on its own background.

    Usage: <x-cta action="report" />  <x-cta action="register" on="dark" block />
--}}
@props([
    'action' => 'report',
    'on' => 'light',
    'block' => false,
])

@php
    $href = $action === 'register' ? route('register') : route('reports.create');
    $label = $action === 'register' ? __('ui.register_ngo') : __('ui.report_discrimination');

    $palette = [
        // Filled blue reads as primary on white; gold carries it on the blue hero.
        // Gold uses near-black text — white on #c8a84e is only 2.3:1 and fails WCAG AA.
        'report' => [
            'light' => 'bg-[#014DA4] hover:bg-[#013b7a] text-white border border-transparent shadow-sm',
            'dark' => 'bg-[#c8a84e] hover:bg-[#d8bb6e] text-[#1a1a1a] border border-transparent shadow-lg shadow-black/10',
        ],
        'register' => [
            'light' => 'bg-white hover:bg-blue-50 text-[#014DA4] border border-[#014DA4]',
            'dark' => 'bg-transparent hover:bg-white/10 text-white border border-white/40',
        ],
    ][$action][$on];
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'text-sm font-semibold px-5 py-2.5 rounded-lg transition text-center whitespace-nowrap',
        'focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#c8a84e]',
        $palette,
        'block w-full' => $block,
        'inline-block' => ! $block,
    ]) }}
>
    {{ $label }}
</a>
