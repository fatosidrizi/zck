@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('news.index') }}" class="text-[#014DA4] hover:text-[#013b7a] text-sm font-medium mb-6 inline-block">&larr; Back to News</a>

            <article class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-64 md:h-96 object-cover">
                @endif
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-block px-2 py-0.5 text-xs font-medium bg-[#014DA4]/10 text-[#014DA4] rounded">{{ ucfirst($article->category) }}</span>
                        <span class="text-sm text-gray-400">{{ $article->published_at?->format('M d, Y') }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $article->title }}</h1>
                    <div class="prose max-w-none text-gray-600">
                        {!! $article->body !!}
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection
