@extends('layouts.app')

@section('title', __('ui.nav_home'))

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-[#014DA4] text-white overflow-hidden min-h-[480px] flex items-center">
        {{-- Background pattern --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#01306a] via-[#014DA4] to-[#0160c9]"></div>
        <div class="absolute inset-0 opacity-[0.04]" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>');"></div>
        {{-- Gold accent line --}}
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c8a84e] via-[#e0c76e] to-[#c8a84e]"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 w-full">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-[3.25rem] font-bold mb-5 leading-[1.15] tracking-tight">{{ __('ui.hero_title') }}</h1>
                <p class="text-base md:text-lg text-blue-100/90 mb-9 leading-relaxed max-w-xl">
                    {{ __('ui.hero_subtitle') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <x-cta action="report" on="dark" class="!px-7 !py-3.5 !text-base" />
                    <x-cta action="register" on="dark" class="!px-7 !py-3.5 !text-base" />
                </div>
            </div>
        </div>
    </section>

    {{-- Statistics Bar --}}
    <section class="bg-white relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 md:p-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 text-center">
                    <div class="relative">
                        <div class="text-4xl md:text-5xl font-extrabold text-[#014DA4] counter" data-target="{{ $stats['ngos'] }}">0</div>
                        <div class="text-xs text-gray-400 mt-2 uppercase tracking-[0.15em] font-semibold">{{ __('ui.stats_ngos') }}</div>
                    </div>
                    <div class="relative">
                        <div class="text-4xl md:text-5xl font-extrabold text-[#014DA4] counter" data-target="{{ $stats['communities'] }}">0</div>
                        <div class="text-xs text-gray-400 mt-2 uppercase tracking-[0.15em] font-semibold">{{ __('ui.stats_communities') }}</div>
                    </div>
                    <div class="relative">
                        <div class="text-4xl md:text-5xl font-extrabold text-[#014DA4] counter" data-target="{{ $stats['donors'] }}">0</div>
                        <div class="text-xs text-gray-400 mt-2 uppercase tracking-[0.15em] font-semibold">{{ __('ui.stats_donors') }}</div>
                    </div>
                    <div class="relative">
                        <div class="text-4xl md:text-5xl font-extrabold text-[#014DA4] counter" data-target="{{ $stats['events'] }}">0</div>
                        <div class="text-xs text-gray-400 mt-2 uppercase tracking-[0.15em] font-semibold">{{ __('ui.stats_events') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Four Value Pillars --}}
    <section class="pt-16 pb-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="group bg-gradient-to-b from-gray-50 to-white rounded-xl border border-gray-100 p-7 text-center hover:shadow-lg hover:shadow-[#014DA4]/5 hover:border-[#014DA4]/20 transition-all duration-300">
                    <div class="w-16 h-16 bg-[#014DA4] rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#014DA4]/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base">{{ __('ui.minorities') }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('ui.minorities_desc') }}</p>
                </div>
                <div class="group bg-gradient-to-b from-gray-50 to-white rounded-xl border border-gray-100 p-7 text-center hover:shadow-lg hover:shadow-[#c8a84e]/5 hover:border-[#c8a84e]/20 transition-all duration-300">
                    <div class="w-16 h-16 bg-[#c8a84e] rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#c8a84e]/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base">{{ __('ui.donations') }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('ui.donations_desc') }}</p>
                </div>
                <div class="group bg-gradient-to-b from-gray-50 to-white rounded-xl border border-gray-100 p-7 text-center hover:shadow-lg hover:shadow-[#014DA4]/5 hover:border-[#014DA4]/20 transition-all duration-300">
                    <div class="w-16 h-16 bg-[#014DA4] rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#014DA4]/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base">{{ __('ui.equality') }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('ui.equality_desc') }}</p>
                </div>
                <div class="group bg-gradient-to-b from-gray-50 to-white rounded-xl border border-gray-100 p-7 text-center hover:shadow-lg hover:shadow-[#c8a84e]/5 hover:border-[#c8a84e]/20 transition-all duration-300">
                    <div class="w-16 h-16 bg-[#c8a84e] rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#c8a84e]/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base">{{ __('ui.reporting') }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ __('ui.reporting_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest News --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('ui.latest_news') }}</h2>
                    <div class="w-12 h-1 bg-[#c8a84e] rounded-full mt-3"></div>
                </div>
                <a href="{{ route('news.index') }}" class="text-[#014DA4] hover:text-[#013b7a] font-semibold text-sm flex items-center gap-1 group">
                    {{ __('ui.view_all') }} <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                @forelse($latestNews as $article)
                    <article class="group bg-white rounded-xl overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 border border-gray-100">
                        <div class="overflow-hidden">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->translated('title') }}" class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @else
                                <div class="w-full h-52 bg-gradient-to-br from-[#014DA4]/5 via-[#014DA4]/10 to-[#c8a84e]/5 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-[#014DA4]/15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold bg-[#014DA4]/8 text-[#014DA4] rounded-full uppercase tracking-wide">{{ ucfirst($article->category) }}</span>
                                <span class="text-xs text-gray-400">{{ $article->published_at?->format('M d, Y') }}</span>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 text-[15px] leading-snug" @if($article->isTranslationFallback('title')) lang="{{ $article->translationLocale('title') }}" @endif>
                                <a href="{{ route('news.show', $article->slug) }}" class="hover:text-[#014DA4] transition">{{ $article->translated('title') }}</a>
                            </h3>
                        </div>
                    </article>
                @empty
                    <p class="text-gray-400 col-span-3 text-center py-8">{{ __('ui.no_news') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Open Public Calls --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('ui.open_public_calls') }}</h2>
                    <div class="w-12 h-1 bg-[#c8a84e] rounded-full mt-3"></div>
                </div>
                <a href="{{ route('public-calls.index') }}" class="text-[#014DA4] hover:text-[#013b7a] font-semibold text-sm flex items-center gap-1 group">
                    {{ __('ui.view_all') }} <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($latestCalls as $call)
                    <a href="{{ route('public-calls.show', $call->slug) }}" class="flex items-center justify-between p-5 bg-gray-50 rounded-xl hover:bg-[#014DA4]/[0.03] hover:border-[#014DA4]/20 transition-all duration-200 border border-gray-100 group">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold bg-[#c8a84e]/10 text-[#c8a84e] rounded-full uppercase tracking-wide mb-1.5">{{ ucfirst($call->type) }}</span>
                            <h3 class="font-semibold text-gray-900 group-hover:text-[#014DA4] transition">{{ $call->title }}</h3>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            @if($call->deadline)
                                <div class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $call->isOpen() ? 'text-emerald-600' : 'text-red-500' }}">
                                    <span class="w-2 h-2 rounded-full {{ $call->isOpen() ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $call->isOpen() ? __('ui.open') : __('ui.closed') }}
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $call->deadline->format('M d, Y') }}</div>
                            @else
                                <div class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    {{ __('ui.open') }}
                                </div>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400 text-center py-8">{{ __('ui.no_calls') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Where to Report --}}
    <section class="py-16 bg-[#014DA4]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3 text-center">{{ __('ui.where_to_report') }}</h2>
            <p class="text-blue-200/70 text-center mb-10 max-w-xl mx-auto text-sm">{{ __('ui.report_discrimination') }}</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <a href="https://www.avokati-popullit.org/" target="_blank" rel="noopener" class="bg-white/10 backdrop-blur-sm rounded-xl border border-white/10 p-7 hover:bg-white/20 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-[#c8a84e] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <h3 class="font-bold text-white mb-2">{{ __('ui.ombudsman') }}</h3>
                    <p class="text-sm text-blue-100/70 leading-relaxed">{{ __('ui.ombudsman_desc') }}</p>
                </a>
                <a href="https://ald.rks-gov.net/" target="_blank" rel="noopener" class="bg-white/10 backdrop-blur-sm rounded-xl border border-white/10 p-7 hover:bg-white/20 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-[#c8a84e] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="font-bold text-white mb-2">{{ __('ui.legal_aid') }}</h3>
                    <p class="text-sm text-blue-100/70 leading-relaxed">{{ __('ui.legal_aid_desc') }}</p>
                </a>
                <a href="https://www.language-commissioner.org/" target="_blank" rel="noopener" class="bg-white/10 backdrop-blur-sm rounded-xl border border-white/10 p-7 hover:bg-white/20 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-[#c8a84e] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    </div>
                    <h3 class="font-bold text-white mb-2">{{ __('ui.language_commissioner') }}</h3>
                    <p class="text-sm text-blue-100/70 leading-relaxed">{{ __('ui.language_commissioner_desc') }}</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Active Organizations --}}
    @if($activeNgos->count())
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('ui.active_organizations') }}</h2>
                    <div class="w-12 h-1 bg-[#c8a84e] rounded-full mt-3"></div>
                </div>
                <a href="{{ route('ngos.index') }}" class="text-[#014DA4] hover:text-[#013b7a] font-semibold text-sm flex items-center gap-1 group">
                    {{ __('ui.view_all') }} <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($activeNgos as $ngo)
                    <div class="group bg-white rounded-xl border border-gray-100 p-6 hover:shadow-lg hover:shadow-gray-200/50 hover:border-[#014DA4]/10 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-3">
                            @if($ngo->logo)
                                <img src="{{ asset('storage/' . $ngo->logo) }}" alt="{{ $ngo->name }}" class="w-14 h-14 object-contain rounded-xl bg-gray-50 p-1" loading="lazy">
                            @else
                                <div class="w-14 h-14 bg-gradient-to-br from-[#014DA4]/10 to-[#014DA4]/5 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#014DA4] font-bold text-xl">{{ substr($ngo->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm group-hover:text-[#014DA4] transition">
                                    <a href="{{ route('ngos.show', $ngo->slug) }}">{{ $ngo->name }}</a>
                                </h3>
                                @if($ngo->location)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $ngo->location }}</p>
                                @endif
                            </div>
                        </div>
                        @if($ngo->category)
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-medium bg-gray-100 text-gray-500 rounded-full">{{ $ngo->category }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Partners & Donors --}}
    <section class="py-12 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-8">{{ __('ui.partners_donors') }}</h3>
            @php
                $partners = [
                    ['file' => 'office-of-the-prime-minister.png', 'name' => 'Office of the Prime Minister', 'class' => 'h-16'],
                    ['file' => 'iom.png', 'name' => 'IOM', 'class' => 'h-10'],
                    ['file' => 'swiss-confederation.png', 'name' => 'Swiss Confederation', 'class' => 'h-10'],
                    ['file' => 'british-embassy-pristina.svg', 'name' => 'British Embassy Pristina', 'class' => 'h-14'],
                    ['file' => 'acdc.png', 'name' => 'ACDC', 'class' => 'h-10'],
                ];
            @endphp
            <div class="flex flex-wrap justify-center items-center gap-x-12 gap-y-8 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                @foreach($partners as $partner)
                    <img src="{{ asset('images/partners/' . $partner['file']) }}"
                         alt="{{ $partner['name'] }}"
                         title="{{ $partner['name'] }}"
                         loading="lazy"
                         class="{{ $partner['class'] }} w-auto max-w-[180px] object-contain">
                @endforeach
                <span class="text-lg font-bold text-gray-400">UNDP</span>
            </div>
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
