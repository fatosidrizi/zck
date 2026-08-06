@extends('layouts.app')

@section('title', __('ui.register_title'))
@section('meta_description', __('ui.register_subtitle'))

@php
    $steps = [
        1 => ['label' => __('ui.step_identity'), 'desc' => __('ui.step_identity_desc')],
        2 => ['label' => __('ui.step_community'), 'desc' => __('ui.step_community_desc')],
        3 => ['label' => __('ui.step_contact'), 'desc' => __('ui.step_contact_desc')],
        4 => ['label' => __('ui.step_review'), 'desc' => __('ui.step_review_desc')],
    ];
@endphp

@section('content')
    <section class="bg-[#014DA4] text-white py-10 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-[#c8a84e] text-xs font-semibold tracking-widest uppercase mb-2">{{ __('ui.official_application') }}</p>
            <h1 class="text-2xl md:text-4xl font-bold mb-2">{{ __('ui.register_title') }}</h1>
            <p class="text-blue-100 md:text-lg max-w-2xl">{{ __('ui.register_subtitle') }}</p>
        </div>
    </section>

    <section class="py-8 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[248px_minmax(0,1fr)] gap-8 lg:gap-12">

                {{-- Mobile progress --}}
                <div class="lg:hidden">
                    <div class="flex items-baseline justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-900">{{ $steps[$step]['label'] }}</p>
                        <p class="text-xs text-gray-500">{{ __('ui.step_counter', ['current' => $step, 'total' => count($steps)]) }}</p>
                    </div>
                    <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-[#014DA4] rounded-full transition-all duration-300" style="width: {{ ($step / count($steps)) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Desktop stepper --}}
                <aside class="hidden lg:block">
                    <nav aria-label="{{ __('ui.registration_steps') }}" class="sticky top-8">
                        <ol class="space-y-1">
                            @foreach($steps as $number => $meta)
                                @php
                                    $isCurrent = $number === $step;
                                    $isDone = $number < $furthestStep;
                                    $isReachable = $number <= $furthestStep;
                                @endphp
                                @php
                                    $itemClass = 'flex gap-3 items-start rounded-lg px-3 py-2.5 transition '
                                        . ($isCurrent ? 'bg-[#014DA4]/5 ' : '')
                                        . ($isReachable && ! $isCurrent ? 'hover:bg-gray-100 ' : '')
                                        . (! $isReachable ? 'opacity-45 ' : '');
                                @endphp
                                <li>
                                    @if($isReachable && ! $isCurrent)
                                        <a href="{{ route('register.step', ['step' => $number]) }}" class="{{ $itemClass }}">
                                            @include('pages.register.partials.stepper-item')
                                        </a>
                                    @else
                                        <div class="{{ $itemClass }}" @if($isCurrent) aria-current="step" @endif>
                                            @include('pages.register.partials.stepper-item')
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ol>

                        <div class="mt-6 pt-6 border-t border-gray-200 px-3">
                            <p class="text-xs font-semibold text-gray-900 mb-1">{{ __('ui.need_help') }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed mb-2">{{ __('ui.register_help_text') }}</p>
                            <a href="{{ route('contact') }}" class="text-xs font-medium text-[#014DA4] hover:underline">{{ __('ui.nav_contact') }} &rarr;</a>
                        </div>
                    </nav>
                </aside>

                <div class="min-w-0">
                    @if($errors->any() && ! $errors->has('submission'))
                        <div role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                            <p class="text-sm font-semibold text-red-800">{{ __('ui.fix_errors') }}</p>
                        </div>
                    @endif

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="px-6 sm:px-8 pt-6 sm:pt-8 pb-5 border-b border-gray-100">
                            <p class="hidden lg:block text-xs font-medium text-gray-400 mb-1">{{ __('ui.step_counter', ['current' => $step, 'total' => count($steps)]) }}</p>
                            <h2 class="text-xl font-bold text-gray-900">{{ $steps[$step]['label'] }}</h2>
                            <p class="text-sm text-gray-500 mt-1">@yield('step_intro', $steps[$step]['desc'])</p>
                        </div>

                        @yield('step_body')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
