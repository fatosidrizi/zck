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
            <form action="{{ route('ngos.index') }}" method="GET" class="mb-8">
                <div class="flex gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or location..."
                           class="flex-1 border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    <button type="submit" class="bg-[#32373c] hover:bg-[#23282d] text-white px-6 py-2.5 rounded transition font-medium">Search</button>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($ngos as $ngo)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="flex items-center gap-4 mb-4">
                            @if($ngo->logo)
                                <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->name }}" class="w-14 h-14 object-contain rounded">
                            @else
                                <div class="w-14 h-14 bg-[#014DA4]/10 rounded flex items-center justify-center">
                                    <span class="text-[#014DA4] font-bold text-xl">{{ substr($ngo->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    <a href="{{ route('ngos.show', $ngo->slug) }}" class="hover:text-[#014DA4] transition">{{ $ngo->name }}</a>
                                </h3>
                                @if($ngo->location)
                                    <p class="text-sm text-gray-500">{{ $ngo->location }}</p>
                                @endif
                            </div>
                        </div>
                        @if($ngo->category)
                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded">{{ $ngo->category }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3">No NGOs found.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $ngos->links() }}</div>
        </div>
    </section>
@endsection
