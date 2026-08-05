@extends('layouts.app')

@section('title', 'News')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">News</h1>
            <p class="text-blue-100 text-lg">Latest news, bulletins, and reports</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-2 mb-8">
                <a href="{{ route('news.index') }}" class="px-4 py-2 rounded text-sm font-medium {{ !request('category') ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">All</a>
                <a href="{{ route('news.index', ['category' => 'news']) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('category') === 'news' ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">News</a>
                <a href="{{ route('news.index', ['category' => 'bulletin']) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('category') === 'bulletin' ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Bulletins</a>
                <a href="{{ route('news.index', ['category' => 'report']) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('category') === 'report' ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Reports</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($articles as $article)
                    <article class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-[#014DA4]/10 to-[#014DA4]/20 flex items-center justify-center">
                                <svg class="w-12 h-12 text-[#014DA4]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-[#014DA4]/10 text-[#014DA4] rounded mb-2">{{ ucfirst($article->category) }}</span>
                            <h3 class="font-semibold text-gray-900 mb-2">
                                <a href="{{ route('news.show', $article->slug) }}" class="hover:text-[#014DA4] transition">{{ $article->title }}</a>
                            </h3>
                            <p class="text-xs text-gray-400">{{ $article->published_at?->format('M d, Y') }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-gray-500 col-span-3">No articles found.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $articles->links() }}</div>
        </div>
    </section>
@endsection
