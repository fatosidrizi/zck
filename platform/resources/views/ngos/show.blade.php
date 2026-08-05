@extends('layouts.app')

@section('title', $ngo->name)

@section('content')
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('ngos.index') }}" class="text-[#014DA4] hover:text-[#013b7a] text-sm font-medium mb-6 inline-block">&larr; Back to NGOs</a>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                <div class="flex items-center gap-4 mb-6">
                    @if($ngo->logo)
                        <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->name }}" class="w-20 h-20 object-contain rounded">
                    @else
                        <div class="w-20 h-20 bg-[#014DA4]/10 rounded flex items-center justify-center">
                            <span class="text-[#014DA4] font-bold text-3xl">{{ substr($ngo->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $ngo->name }}</h1>
                        @if($ngo->location)
                            <p class="text-gray-500">{{ $ngo->location }}</p>
                        @endif
                    </div>
                </div>

                @if($ngo->description)
                    <div class="prose max-w-none text-gray-600 mb-6">
                        {!! nl2br(e($ngo->description)) !!}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    @if($ngo->contact_email)
                        <div><span class="font-medium text-gray-900">Email:</span> <a href="mailto:{{ $ngo->contact_email }}" class="text-[#014DA4]">{{ $ngo->contact_email }}</a></div>
                    @endif
                    @if($ngo->contact_phone)
                        <div><span class="font-medium text-gray-900">Phone:</span> {{ $ngo->contact_phone }}</div>
                    @endif
                    @if($ngo->website)
                        <div><span class="font-medium text-gray-900">Website:</span> <a href="{{ $ngo->website }}" target="_blank" rel="noopener" class="text-[#014DA4]">{{ $ngo->website }}</a></div>
                    @endif
                    @if($ngo->category)
                        <div><span class="font-medium text-gray-900">Category:</span> {{ $ngo->category }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
