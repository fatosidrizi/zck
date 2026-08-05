@extends('layouts.app')

@section('title', 'Events')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Events</h1>
            <p class="text-blue-100 text-lg">Upcoming and past community events</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Community Filter --}}
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('events.index') }}" class="px-4 py-2 rounded text-sm font-medium {{ !request('community') ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">All Communities</a>
                @foreach($communities as $community)
                    <a href="{{ route('events.index', ['community' => $community->id]) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('community') == $community->id ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">{{ $community->name }}</a>
                @endforeach
            </div>

            {{-- Calendar View --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-10">
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('events.index', array_merge(request()->except('month'), ['month' => $calendarMonth->copy()->subMonth()->format('Y-m')])) }}" class="text-[#014DA4] hover:text-[#013b7a] font-medium text-sm">&larr; Previous</a>
                    <h3 class="text-lg font-bold text-gray-900">{{ $calendarMonth->format('F Y') }}</h3>
                    <a href="{{ route('events.index', array_merge(request()->except('month'), ['month' => $calendarMonth->copy()->addMonth()->format('Y-m')])) }}" class="text-[#014DA4] hover:text-[#013b7a] font-medium text-sm">Next &rarr;</a>
                </div>
                <div class="grid grid-cols-7 gap-px bg-gray-200 rounded overflow-hidden">
                    @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                        <div class="bg-gray-50 text-center text-xs font-medium text-gray-500 py-2">{{ $day }}</div>
                    @endforeach
                    @php
                        $start = $calendarMonth->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::MONDAY);
                        $end = $calendarMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
                    @endphp
                    @while($start <= $end)
                        @php
                            $dateKey = $start->format('Y-m-d');
                            $isCurrentMonth = $start->month === $calendarMonth->month;
                            $hasEvents = isset($calendarEvents[$dateKey]);
                            $isToday = $start->isToday();
                        @endphp
                        <div class="bg-white min-h-[70px] p-1.5 {{ !$isCurrentMonth ? 'opacity-30' : '' }}">
                            <div class="text-xs {{ $isToday ? 'bg-[#014DA4] text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-500' }}">
                                {{ $start->day }}
                            </div>
                            @if($hasEvents)
                                @foreach($calendarEvents[$dateKey]->take(2) as $evt)
                                    <a href="{{ route('events.show', $evt->slug) }}" class="block mt-0.5 text-[10px] leading-tight bg-[#014DA4]/10 text-[#014DA4] rounded px-1 py-0.5 truncate hover:bg-[#014DA4]/20">
                                        {{ $evt->title }}
                                    </a>
                                @endforeach
                                @if($calendarEvents[$dateKey]->count() > 2)
                                    <span class="text-[10px] text-gray-400">+{{ $calendarEvents[$dateKey]->count() - 2 }} more</span>
                                @endif
                            @endif
                        </div>
                        @php $start->addDay(); @endphp
                    @endwhile
                </div>
            </div>

            {{-- Upcoming Events --}}
            @if($upcoming->count())
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Upcoming Events</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    @foreach($upcoming as $event)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover" loading="lazy">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                                    <span class="text-green-400 text-sm">Upcoming</span>
                                </div>
                            @endif
                            <div class="p-5">
                                <div class="text-sm text-green-600 font-medium mb-1">{{ $event->event_date->format('M d, Y') }}</div>
                                <h3 class="font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('events.show', $event->slug) }}" class="hover:text-[#014DA4] transition">{{ $event->title }}</a>
                                </h3>
                                @if($event->community)
                                    <p class="text-xs text-[#014DA4]">{{ $event->community->name }}</p>
                                @endif
                                @if($event->location)
                                    <p class="text-sm text-gray-500">{{ $event->location }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Past Events --}}
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Past Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($past as $event)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition opacity-75">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover" loading="lazy">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                                <span class="text-gray-400 text-sm">Past Event</span>
                            </div>
                        @endif
                        <div class="p-5">
                            <div class="text-sm text-gray-400 font-medium mb-1">{{ $event->event_date->format('M d, Y') }}</div>
                            <h3 class="font-semibold text-gray-900 mb-1">
                                <a href="{{ route('events.show', $event->slug) }}" class="hover:text-[#014DA4] transition">{{ $event->title }}</a>
                            </h3>
                            @if($event->community)
                                <p class="text-xs text-gray-400">{{ $event->community->name }}</p>
                            @endif
                            @if($event->location)
                                <p class="text-sm text-gray-500">{{ $event->location }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3">No past events.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $past->links() }}</div>
        </div>
    </section>
@endsection
