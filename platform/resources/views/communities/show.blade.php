@extends('layouts.app')

@section('title', $community->name)

@section('content')
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('communities.index') }}" class="text-[#014DA4] hover:text-[#013b7a] text-sm font-medium mb-6 inline-block">&larr; Back to Communities</a>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                @if($community->image)
                    <img src="{{ asset('storage/' . $community->image) }}" alt="{{ $community->name }}" class="w-full h-64 object-cover">
                @endif
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $community->name }}</h1>
                    <div class="flex gap-4 text-sm text-gray-500 mb-6">
                        @if($community->region)
                            <span>Region: {{ $community->region }}</span>
                        @endif
                        @if($community->population)
                            <span>Population: {{ $community->population }}</span>
                        @endif
                    </div>
                    @if($community->description)
                        <div class="prose max-w-none text-gray-600 mb-8">
                            {!! nl2br(e($community->description)) !!}
                        </div>
                    @endif

                    @if($community->events->count())
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Community Events</h2>
                        <div class="space-y-3">
                            @foreach($community->events()->orderByDesc('event_date')->limit(5)->get() as $event)
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-medium text-gray-900">
                                            <a href="{{ route('events.show', $event->slug) }}" class="hover:text-[#014DA4]">{{ $event->title }}</a>
                                        </h3>
                                        @if($event->location)
                                            <p class="text-sm text-gray-500">{{ $event->location }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $event->event_date->format('M d, Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
