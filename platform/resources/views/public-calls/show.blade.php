@extends('layouts.app')

@section('title', $call->title)

@section('content')
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('public-calls.index') }}" class="text-[#014DA4] hover:text-[#013b7a] text-sm font-medium mb-6 inline-block">&larr; Back to Public Calls</a>

            <article class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="inline-block px-2 py-0.5 text-xs font-medium bg-[#c8a84e]/10 text-[#c8a84e] rounded">{{ ucfirst($call->type) }}</span>
                    <span class="inline-block px-2 py-0.5 text-xs font-medium {{ $call->isOpen() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded">
                        {{ $call->isOpen() ? 'Open' : 'Closed' }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $call->title }}</h1>
                @if($call->deadline)
                    <p class="text-sm text-gray-500 mb-6">Deadline: {{ $call->deadline->format('F d, Y') }}</p>
                @endif
                <div class="prose max-w-none text-gray-600 mb-6">
                    {!! nl2br(e($call->body)) !!}
                </div>
                @if($call->attachment)
                    <a href="{{ asset('storage/' . $call->attachment) }}" class="inline-flex items-center gap-2 bg-[#32373c] hover:bg-[#23282d] text-white px-5 py-2.5 rounded transition" download>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Attachment
                    </a>
                @endif
            </article>
        </div>
    </section>
@endsection
