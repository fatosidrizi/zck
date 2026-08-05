@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('events.index') }}" class="text-[#014DA4] hover:text-[#013b7a] text-sm font-medium mb-6 inline-block">&larr; Back to Events</a>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
                @endif
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-6">
                        <span>Date: {{ $event->event_date->format('F d, Y') }}</span>
                        @if($event->event_time)
                            <span>Time: {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                        @endif
                        @if($event->location)
                            <span>Location: {{ $event->location }}</span>
                        @endif
                        @if($event->community)
                            <span>Community: <a href="{{ route('communities.show', $event->community->slug) }}" class="text-[#014DA4]">{{ $event->community->name }}</a></span>
                        @endif
                    </div>
                    @if($event->description)
                        <div class="prose max-w-none text-gray-600">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
