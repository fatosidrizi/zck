@extends('layouts.app')

@section('title', 'Communities')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Communities</h1>
            <p class="text-blue-100 text-lg">Explore the communities of Kosovo</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($communities as $community)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        @if($community->image)
                            <img src="{{ asset('storage/' . $community->image) }}" alt="{{ $community->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-[#014DA4]/10 to-[#c8a84e]/10 flex items-center justify-center">
                                <span class="text-[#014DA4]/40 text-4xl font-bold">{{ substr($community->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-900 text-lg mb-1">
                                <a href="{{ route('communities.show', $community->slug) }}" class="hover:text-[#014DA4] transition">{{ $community->name }}</a>
                            </h3>
                            @if($community->region)
                                <p class="text-sm text-gray-500">{{ $community->region }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3">No communities found.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
