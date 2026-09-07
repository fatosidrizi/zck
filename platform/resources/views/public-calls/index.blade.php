@extends('layouts.app')

@section('title', __('ui.public_calls_title'))

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ __('ui.public_calls_title') }}</h1>
            <p class="text-blue-100 text-lg">{{ __('ui.public_calls_subtitle') }}</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $types = [
                    null => __('ui.all'),
                    'recruitment' => __('ui.recruitment'),
                    'grant' => __('ui.grants'),
                    'funding' => __('ui.funding'),
                    'commission' => __('ui.commissions'),
                ];
            @endphp

            <div class="flex flex-wrap gap-2 mb-8">
                @foreach($types as $type => $label)
                    <a href="{{ route('public-calls.index', $type ? ['type' => $type] : []) }}"
                       class="rounded px-4 py-2 text-sm font-medium transition {{ request('type') === $type ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $label }}</a>
                @endforeach
            </div>

            <div class="space-y-3">
                @forelse($calls as $call)
                    @php
                        $title = $call->translated('title');
                        $fallbackLocale = $call->isTranslationFallback('title') ? $call->translationLocale('title') : null;
                    @endphp
                    <a href="{{ route('public-calls.show', $call->slug) }}"
                       class="group flex items-start justify-between gap-4 rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-[#014DA4]/40 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#014DA4]">
                        <div class="min-w-0 flex-1">
                            <div class="mb-1.5 flex flex-wrap items-center gap-2">
                                <span class="rounded bg-[#c8a84e]/10 px-2 py-0.5 text-xs font-medium text-[#c8a84e]">{{ $types[$call->type] ?? ucfirst($call->type) }}</span>
                                @if($fallbackLocale)
                                    <span class="rounded bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">
                                        {{ __('ui.only_in_language', ['language' => \App\Support\Locales::label($fallbackLocale)]) }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="font-semibold leading-snug text-gray-900 transition group-hover:text-[#014DA4]"
                                @if($fallbackLocale) lang="{{ $fallbackLocale }}" @endif>{{ $title }}</h3>
                        </div>
                        <div class="shrink-0 text-right">
                            <div class="text-sm font-medium {{ $call->isOpen() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $call->isOpen() ? __('ui.open') : __('ui.closed') }}
                            </div>
                            @if($call->deadline)
                                <div class="text-xs text-gray-400">{{ $call->deadline->format('M d, Y') }}</div>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500">{{ __('ui.no_calls_found') }}</p>
                @endforelse
            </div>

            @if($calls->hasPages())
                <div class="mt-8">{{ $calls->links() }}</div>
            @endif
        </div>
    </section>
@endsection
