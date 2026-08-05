@extends('layouts.app')

@section('title', 'Home')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-[#014DA4] text-white overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#014DA4] to-[#01306a] opacity-90"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="max-w-3xl">
                <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight">Për një të nesërme<br>më të ndritur</h1>
                <p class="text-lg md:text-xl text-blue-100 mb-8 leading-relaxed">
                    For a brighter tomorrow — mobilizing and organizing citizens through strategic planning
                    to solve community problems.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('register') }}" class="bg-[#c8a84e] hover:bg-[#b8942e] text-white px-8 py-3 rounded font-semibold transition text-center">
                        Register Your Organization
                    </a>
                    <a href="{{ route('reports.create') }}" class="bg-[#32373c] hover:bg-[#23282d] text-white px-8 py-3 rounded font-semibold transition text-center">
                        Report Discrimination
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistics Bar --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-[#014DA4] counter" data-target="{{ $stats['ngos'] }}">0</div>
                    <div class="text-sm text-gray-500 mt-1 uppercase tracking-wider">NGOs</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-[#014DA4] counter" data-target="{{ $stats['communities'] }}">0</div>
                    <div class="text-sm text-gray-500 mt-1 uppercase tracking-wider">Communities</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-[#014DA4] counter" data-target="{{ $stats['donors'] }}">0</div>
                    <div class="text-sm text-gray-500 mt-1 uppercase tracking-wider">Donors</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-[#014DA4] counter" data-target="{{ $stats['events'] }}">0</div>
                    <div class="text-sm text-gray-500 mt-1 uppercase tracking-wider">Events</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Four Value Pillars --}}
    <section class="py-14 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-[#014DA4] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Minorities</h3>
                    <p class="text-sm text-gray-500">Supporting and empowering minority communities across Kosovo</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-[#c8a84e] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Donations</h3>
                    <p class="text-sm text-gray-500">Facilitating donor support and funding for community projects</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-[#014DA4] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Equality</h3>
                    <p class="text-sm text-gray-500">Promoting equal rights and justice for all communities</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-[#c8a84e] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Reporting</h3>
                    <p class="text-sm text-gray-500">A safe platform for reporting discrimination and seeking assistance</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest News --}}
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Latest News</h2>
                <a href="{{ route('news.index') }}" class="text-[#014DA4] hover:text-[#013b7a] font-medium text-sm">View All &rarr;</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($latestNews as $article)
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
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('news.show', $article->slug) }}" class="hover:text-[#014DA4] transition">{{ $article->title }}</a>
                            </h3>
                            <p class="text-xs text-gray-400">{{ $article->published_at?->format('M d, Y') }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-gray-500 col-span-3">No news articles published yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Open Public Calls --}}
    <section class="py-14 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Open Public Calls</h2>
                <a href="{{ route('public-calls.index') }}" class="text-[#014DA4] hover:text-[#013b7a] font-medium text-sm">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($latestCalls as $call)
                    <div class="flex items-center justify-between p-5 bg-white rounded-lg border border-gray-100 hover:shadow-sm transition">
                        <div>
                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-[#c8a84e]/10 text-[#c8a84e] rounded mb-1">{{ ucfirst($call->type) }}</span>
                            <h3 class="font-semibold text-gray-900">
                                <a href="{{ route('public-calls.show', $call->slug) }}" class="hover:text-[#014DA4] transition">{{ $call->title }}</a>
                            </h3>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            @if($call->deadline)
                                <div class="text-sm font-medium {{ $call->isOpen() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $call->isOpen() ? 'Open' : 'Closed' }}
                                </div>
                                <div class="text-xs text-gray-400">{{ $call->deadline->format('M d, Y') }}</div>
                            @else
                                <div class="text-sm font-medium text-green-600">Open</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No public calls published yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Reporting / Support Resources --}}
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Where to Report Discrimination</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="https://www.avokati-popullit.org/" target="_blank" rel="noopener" class="bg-gray-50 rounded-lg border border-gray-100 p-6 hover:border-[#014DA4] hover:shadow-sm transition group">
                    <h3 class="font-bold text-gray-900 mb-2 group-hover:text-[#014DA4]">Ombudsman Office</h3>
                    <p class="text-sm text-gray-500">Institution for the protection of human rights and fundamental freedoms</p>
                </a>
                <a href="https://ald.rks-gov.net/" target="_blank" rel="noopener" class="bg-gray-50 rounded-lg border border-gray-100 p-6 hover:border-[#014DA4] hover:shadow-sm transition group">
                    <h3 class="font-bold text-gray-900 mb-2 group-hover:text-[#014DA4]">Agency for Legal Aid</h3>
                    <p class="text-sm text-gray-500">Free legal assistance for citizens who cannot afford legal representation</p>
                </a>
                <a href="https://www.language-commissioner.org/" target="_blank" rel="noopener" class="bg-gray-50 rounded-lg border border-gray-100 p-6 hover:border-[#014DA4] hover:shadow-sm transition group">
                    <h3 class="font-bold text-gray-900 mb-2 group-hover:text-[#014DA4]">Language Commissioner</h3>
                    <p class="text-sm text-gray-500">Ensuring the rights of all communities to use their official languages</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Active Organizations --}}
    @if($activeNgos->count())
    <section class="py-14 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Active Organizations</h2>
                <a href="{{ route('ngos.index') }}" class="text-[#014DA4] hover:text-[#013b7a] font-medium text-sm">View All &rarr;</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($activeNgos as $ngo)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center gap-3 mb-3">
                            @if($ngo->logo)
                                <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->name }}" class="w-12 h-12 object-contain rounded">
                            @else
                                <div class="w-12 h-12 bg-[#014DA4]/10 rounded flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#014DA4] font-bold text-lg">{{ substr($ngo->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">
                                    <a href="{{ route('ngos.show', $ngo->slug) }}" class="hover:text-[#014DA4] transition">{{ $ngo->name }}</a>
                                </h3>
                                @if($ngo->location)
                                    <p class="text-xs text-gray-400">{{ $ngo->location }}</p>
                                @endif
                            </div>
                        </div>
                        @if($ngo->category)
                            <span class="inline-block px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">{{ $ngo->category }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Donor / Partner Logos placeholder --}}
    <section class="py-10 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-sm uppercase tracking-wider text-gray-400 mb-6">Our Partners & Donors</h3>
            <div class="flex flex-wrap justify-center items-center gap-8 opacity-50">
                <span class="text-sm text-gray-400 border border-gray-200 rounded px-6 py-3">IOM</span>
                <span class="text-sm text-gray-400 border border-gray-200 rounded px-6 py-3">Swiss Embassy</span>
                <span class="text-sm text-gray-400 border border-gray-200 rounded px-6 py-3">British Embassy</span>
                <span class="text-sm text-gray-400 border border-gray-200 rounded px-6 py-3">ACDC</span>
                <span class="text-sm text-gray-400 border border-gray-200 rounded px-6 py-3">UNDP</span>
            </div>
            <p class="text-xs text-gray-400 mt-4">Replace with actual donor logos in admin</p>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-target'));
                if (target === 0) { el.textContent = '0'; return; }
                const duration = 1500;
                const step = Math.ceil(target / (duration / 16));
                let current = 0;
                const timer = setInterval(function() {
                    current += step;
                    if (current >= target) { current = target; clearInterval(timer); }
                    el.textContent = current;
                }, 16);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.3 });
    counters.forEach(function(c) { observer.observe(c); });
});
</script>
@endpush
