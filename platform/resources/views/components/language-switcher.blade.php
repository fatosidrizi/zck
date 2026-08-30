{{--
    Segmented locale toggle. Shows every locale at once with the active one
    highlighted, so it is never ambiguous whether a code means "you are here"
    or "go here" — the failure mode of a single flip-flop chip.

    Adding a locale to SetLocale::SUPPORTED_LOCALES is enough; this follows.
--}}
@props(['compact' => false])

@php
    use App\Http\Middleware\SetLocale;

    $current = app()->getLocale();
    $path = request()->path();
    $query = request()->getQueryString();

    // Endonyms: a language is always listed in its own language.
    $names = [
        'en' => ['full' => 'English', 'short' => 'EN'],
        'sq' => ['full' => 'Shqip', 'short' => 'SQ'],
        'sr' => ['full' => 'Srpski', 'short' => 'SR'],
        // The /ro/ prefix is inherited from the old site; the language is Romani.
        'ro' => ['full' => 'Romani chib', 'short' => 'RO'],
        'bs' => ['full' => 'Bosanski', 'short' => 'BS'],
        'tr' => ['full' => 'Türkçe', 'short' => 'TR'],
    ];

    $locales = collect(SetLocale::SUPPORTED_LOCALES)->map(function ($locale) use ($current, $path, $query, $names) {
        $target = preg_replace('#^(' . preg_quote($current, '#') . ')(/|$)#', $locale . '$2', $path);

        return [
            'code' => $locale,
            'tag' => SetLocale::languageTag($locale),
            'label' => $names[$locale]['short'] ?? Str::upper($locale),
            'title' => $names[$locale]['full'] ?? Str::upper($locale),
            'url' => url($target) . ($query ? '?' . $query : ''),
            'active' => $locale === $current,
        ];
    });
@endphp

<div {{ $attributes->class([
        'inline-flex items-center rounded border border-white/30 overflow-hidden',
        'font-semibold uppercase leading-none',
        'text-[10px]' => $compact,
        'text-[11px]' => ! $compact,
     ]) }}
     role="group"
     aria-label="{{ __('ui.language') }}">
    @foreach($locales as $locale)
        <a href="{{ $locale['url'] }}"
           lang="{{ $locale['tag'] }}"
           hreflang="{{ $locale['tag'] }}"
           title="{{ $locale['title'] }}"
           @if($locale['active']) aria-current="true" @endif
           @class([
               'px-2 py-1 transition',
               'bg-white text-[#014DA4]' => $locale['active'],
               'text-white/80 hover:bg-white/15 hover:text-white' => ! $locale['active'],
               'border-l border-white/30' => ! $loop->first,
           ])>
            <span class="sr-only">{{ $locale['title'] }} &mdash; </span>
            <span aria-hidden="true">{{ $locale['label'] }}</span>
        </a>
    @endforeach
</div>
