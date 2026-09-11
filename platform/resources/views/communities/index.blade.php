@extends('layouts.app')

@section('title', __('ui.communities_title'))

@section('content')
    {{-- Intro --}}
    <section class="relative bg-[#014DA4] text-white overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#01306a] via-[#014DA4] to-[#0160c9]"></div>
        <div class="absolute inset-0 opacity-[0.04]" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>');"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-28 md:pt-20 md:pb-32 text-center">
            <h1 class="text-3xl md:text-[2.75rem] font-bold leading-tight tracking-tight mb-4">{{ __('ui.communities_title') }}</h1>
            <div class="w-12 h-1 bg-[#c8a84e] rounded-full mx-auto mb-5"></div>
            <p class="text-blue-100/90 text-lg max-w-2xl mx-auto">{{ __('ui.communities_subtitle') }}</p>
        </div>
    </section>

    {{-- Values --}}
    @php
        $values = [
            ['title' => __('ui.sustainability'), 'desc' => __('ui.sustainability_desc'), 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['title' => __('ui.inclusion'), 'desc' => __('ui.inclusion_desc'), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['title' => __('ui.participation'), 'desc' => __('ui.participation_desc'), 'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
        ];
    @endphp
    <section class="bg-gray-50 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 md:-mt-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($values as $value)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 p-7 md:p-8">
                        <div class="w-12 h-12 bg-[#c8a84e] rounded-xl flex items-center justify-center mb-5 shadow-lg shadow-[#c8a84e]/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $value['icon'] }}"/></svg>
                        </div>
                        <h2 class="font-bold text-gray-900 text-lg mb-2">{{ $value['title'] }}</h2>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Community list --}}
    <section class="py-16 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">
                <div class="lg:col-span-4 lg:sticky lg:top-28">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('ui.minority_communities') }}</h2>
                    <div class="w-12 h-1 bg-[#c8a84e] rounded-full mt-3 mb-5"></div>
                    <p class="text-gray-500 leading-relaxed">{{ __('ui.minority_communities_intro') }}</p>
                </div>

                <div class="lg:col-span-8">
                    @if($communities->count())
                        <ul class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100 overflow-hidden">
                            @foreach($communities as $community)
                                @php
                                    $name = $community->translated('name');
                                    $fallbackLocale = $community->isTranslationFallback('name') ? $community->translationLocale('name') : null;
                                @endphp
                                <li>
                                    <a href="{{ route('communities.show', $community->slug) }}"
                                       class="group flex items-center gap-4 px-5 md:px-6 py-4 hover:bg-[#014DA4]/[0.03] transition focus:outline-none focus-visible:bg-[#014DA4]/[0.05]">
                                        <span class="w-10 h-10 shrink-0 rounded-lg bg-[#014DA4]/10 text-[#014DA4] font-bold flex items-center justify-center group-hover:bg-[#014DA4] group-hover:text-white transition" aria-hidden="true">{{ mb_strtoupper(mb_substr($name, 0, 1)) }}</span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block font-semibold text-gray-900 group-hover:text-[#014DA4] transition" @if($fallbackLocale) lang="{{ $fallbackLocale }}" @endif>{{ $name }}</span>
                                            @if($community->region)
                                                <span class="block text-xs text-gray-500 mt-0.5 truncate">{{ $community->region }}</span>
                                            @endif
                                        </span>
                                        <svg class="w-5 h-5 shrink-0 text-gray-300 group-hover:text-[#014DA4] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center text-gray-500">
                            {{ __('ui.no_communities') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
