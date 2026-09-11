@extends('layouts.app')

@php
    $name = $community->translated('name');
    $description = $community->translated('description');
    $fallbackLocale = $community->isTranslationFallback('name') ? $community->translationLocale('name') : null;
    $imageUrl = $community->imageUrl();
    $events = $community->events()->orderByDesc('event_date')->limit(5)->get();
@endphp

@section('title', $name)

@section('content')
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('communities.index') }}" class="text-[#014DA4] hover:text-[#013b7a] text-sm font-medium mb-6 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ __('ui.back_to_communities') }}
            </a>

            <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @if($imageUrl)
                    <div class="relative h-64 md:h-80 bg-gray-100">
                        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <h1 class="absolute left-8 right-8 bottom-6 text-3xl md:text-4xl font-bold text-white drop-shadow" @if($fallbackLocale) lang="{{ $fallbackLocale }}" @endif>{{ $name }}</h1>
                    </div>
                @endif
                <div class="p-8">
                    @unless($imageUrl)
                        <h1 class="text-3xl font-bold text-gray-900 mb-4" @if($fallbackLocale) lang="{{ $fallbackLocale }}" @endif>{{ $name }}</h1>
                    @endunless

                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        @if($community->region)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#014DA4]/10 px-3 py-1 text-sm font-medium text-[#014DA4]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ __('ui.region') }}: {{ $community->region }}
                            </span>
                        @endif
                        @if($fallbackLocale)
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-500">
                                {{ __('ui.only_in_language', ['language' => \App\Support\Locales::label($fallbackLocale)]) }}
                            </span>
                        @endif
                    </div>

                    @if($description)
                        <div class="prose max-w-none text-gray-600 leading-relaxed mb-10" @if($community->isTranslationFallback('description')) lang="{{ $community->translationLocale('description') }}" @endif>
                            {!! nl2br(e($description)) !!}
                        </div>
                    @endif

                    @if($events->count())
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('ui.community_events') }}</h2>
                        <div class="w-10 h-1 bg-[#c8a84e] rounded-full mb-5"></div>
                        <div class="space-y-3">
                            @foreach($events as $event)
                                <a href="{{ route('events.show', $event->slug) }}" class="flex justify-between items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100 hover:border-[#014DA4]/30 hover:bg-[#014DA4]/[0.03] transition group">
                                    <div class="min-w-0">
                                        <h3 class="font-medium text-gray-900 group-hover:text-[#014DA4] transition truncate">{{ $event->translated('title') }}</h3>
                                        @if($event->location)
                                            <p class="text-sm text-gray-500">{{ $event->location }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500 shrink-0">{{ $event->event_date->format('M d, Y') }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection
