@extends('layouts.app')

@section('title', 'NGO Directory')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">NGO Directory</h1>
            <p class="text-blue-100 text-lg">Browse registered non-governmental organizations</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('ngos.index') }}" method="GET">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                            </svg>
                        </span>
                        <label for="search" class="sr-only">Search NGOs</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, location or category..."
                               class="w-full rounded-lg border border-gray-300 bg-white py-3 pl-11 pr-4 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-[#014DA4] focus:ring-2 focus:ring-[#014DA4]/25">
                    </div>
                    <button type="submit" class="rounded-lg bg-[#32373c] px-7 py-3 font-medium text-white shadow-sm transition hover:bg-[#23282d] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#014DA4]">Search</button>
                </div>
            </form>

            <div class="mt-6 mb-5 flex flex-wrap items-baseline gap-x-2 gap-y-1 text-sm text-gray-600">
                <span><span class="font-semibold text-gray-900">{{ $ngos->total() }}</span> {{ Str::plural('organization', $ngos->total()) }}</span>
                @if(request('search'))
                    <span class="text-gray-400">&middot;</span>
                    <span>matching <span class="font-medium text-gray-900">"{{ request('search') }}"</span></span>
                    <a href="{{ route('ngos.index') }}" class="text-[#014DA4] hover:underline">Clear</a>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($ngos as $ngo)
                    <a href="{{ route('ngos.show', $ngo->slug) }}"
                       class="group flex h-full flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#014DA4]/40 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[#014DA4] focus-visible:ring-offset-2">
                        <div class="flex items-start gap-3.5">
                            @if($ngo->logo)
                                <img src="{{ asset('storage/' . $ngo->logo) }}" alt=""
                                     class="h-11 w-11 shrink-0 rounded-lg border border-gray-100 object-contain">
                            @else
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-[#014DA4]/10 text-base font-bold text-[#014DA4]">
                                    {{ mb_strtoupper(mb_substr($ngo->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="line-clamp-2 min-h-[2.6rem] text-[15px] font-semibold leading-[1.35] text-gray-900 transition group-hover:text-[#014DA4]"
                                    title="{{ $ngo->name }}">{{ $ngo->name }}</h3>
                                @if($ngo->location)
                                    <p class="mt-1 flex items-center gap-1.5 text-sm text-gray-500">
                                        <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $ngo->location }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($ngo->description)
                            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-gray-600">{{ $ngo->description }}</p>
                        @endif

                        <div class="mt-4 flex grow items-end justify-between gap-3 border-t border-gray-100 pt-3.5">
                            <div class="flex min-w-0 flex-wrap items-center gap-1.5">
                                @if($ngo->primary_community)
                                    <span class="rounded-full bg-[#014DA4]/10 px-2.5 py-1 text-xs font-medium text-[#014DA4]">{{ Str::title($ngo->primary_community) }}</span>
                                @endif
                                @if($ngo->category)
                                    <span class="truncate rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">{{ $ngo->category }}</span>
                                @endif
                            </div>
                            <span class="flex shrink-0 items-center gap-1 text-sm font-medium text-[#014DA4] opacity-0 transition group-hover:opacity-100 group-focus-visible:opacity-100">
                                View
                                <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
                        <p class="font-medium text-gray-700">No NGOs found.</p>
                        <p class="mt-1 text-sm text-gray-500">Try a different search term or browse the full directory.</p>
                    </div>
                @endforelse
            </div>

            @if($ngos->hasPages())
                <div class="mt-10">{{ $ngos->links() }}</div>
            @endif
        </div>
    </section>
@endsection
