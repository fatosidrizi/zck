@extends('layouts.app')

@section('title', __('ui.registration_submitted'))

@section('content')
    <section class="py-14 md:py-20">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-14 h-14 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ __('ui.registration_submitted') }}</h1>
            <p class="text-gray-600 mb-8">{{ __('ui.registration_submitted_body') }}</p>

            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">{{ __('ui.reference_number') }}</p>
                <p class="text-2xl md:text-3xl font-bold text-[#014DA4] tracking-wider select-all">{{ $referenceNumber }}</p>
                <p class="text-xs text-gray-500 mt-3">{{ __('ui.reference_number_help') }}</p>
            </div>

            <div class="text-left bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">{{ __('ui.what_happens_next') }}</h2>
                <ol class="space-y-3">
                    @foreach([__('ui.next_step_1'), __('ui.next_step_2'), __('ui.next_step_3')] as $index => $line)
                        <li class="flex gap-3 text-sm text-gray-600">
                            <span class="shrink-0 w-5 h-5 rounded-full bg-[#014DA4]/10 text-[#014DA4] text-xs font-semibold flex items-center justify-center">{{ $index + 1 }}</span>
                            <span class="leading-relaxed">{{ $line }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register.track', ['reference' => $referenceNumber]) }}" class="bg-[#014DA4] hover:bg-[#013a7d] text-white font-semibold px-6 py-2.5 rounded-lg transition">
                    {{ __('ui.check_status') }}
                </a>
                <a href="{{ route('ngos.index') }}" class="border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-6 py-2.5 rounded-lg transition">
                    {{ __('ui.ngo_directory') }}
                </a>
                <a href="{{ route('home') }}" class="border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-6 py-2.5 rounded-lg transition">
                    {{ __('ui.nav_home') }}
                </a>
            </div>
        </div>
    </section>
@endsection
