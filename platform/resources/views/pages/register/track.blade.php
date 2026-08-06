@extends('layouts.app')

@section('title', __('ui.track_registration_title'))

@section('content')
    <section class="bg-[#014DA4] text-white py-10 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-4xl font-bold mb-2">{{ __('ui.track_registration_title') }}</h1>
            <p class="text-blue-100 md:text-lg max-w-2xl">{{ __('ui.track_registration_subtitle') }}</p>
        </div>
    </section>

    <section class="py-10 md:py-12">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('register.track') }}" method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8 mb-6">
                <label for="reference" class="block text-sm font-medium text-gray-800 mb-1.5">{{ __('ui.reference_number') }}</label>
                <input type="text" name="reference" id="reference" required
                       value="{{ request('reference') }}"
                       placeholder="NGO-2026-XXXXXX"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 uppercase outline-none transition focus:ring-2 focus:ring-[#014DA4]/30 focus:border-[#014DA4]">
                <button type="submit" class="mt-4 w-full bg-[#014DA4] hover:bg-[#013a7d] text-white font-semibold py-2.5 rounded-lg transition">
                    {{ __('ui.check_status') }}
                </button>
            </form>

            @if($ngo)
                @php
                    [$badgeClass, $statusLabel, $explanation] = match (true) {
                        $ngo->isPending() => ['bg-amber-100 text-amber-800', __('ui.status_pending'), __('ui.status_pending_help')],
                        $ngo->isClosed() => ['bg-red-100 text-red-800', __('ui.status_closed'), __('ui.status_closed_help')],
                        $ngo->is_active => ['bg-green-100 text-green-800', __('ui.status_published'), __('ui.status_published_help')],
                        default => ['bg-gray-100 text-gray-700', __('ui.status_approved'), __('ui.status_approved_help')],
                    };
                @endphp

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div class="min-w-0">
                            <h2 class="text-lg font-bold text-gray-900 truncate">{{ $ngo->name }}</h2>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $ngo->reference_number }}</p>
                        </div>
                        <span class="shrink-0 inline-block px-3 py-1 text-sm font-medium rounded-full {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </div>

                    <dl class="grid sm:grid-cols-[160px_minmax(0,1fr)] gap-x-6 gap-y-2 text-sm border-t border-gray-100 pt-5">
                        <dt class="text-gray-500">{{ __('ui.submitted_on') }}</dt>
                        <dd class="text-gray-900 mb-2 sm:mb-0">{{ $ngo->submitted_at?->format('d M Y') }}</dd>

                        <dt class="text-gray-500">{{ __('ui.last_updated') }}</dt>
                        <dd class="text-gray-900">{{ $ngo->updated_at->format('d M Y') }}</dd>
                    </dl>

                    <p class="text-sm text-gray-600 leading-relaxed mt-5 pt-5 border-t border-gray-100">{{ $explanation }}</p>

                    @if($ngo->isApproved() && $ngo->is_active)
                        <a href="{{ route('ngos.show', $ngo->slug) }}" class="inline-block mt-4 text-sm font-medium text-[#014DA4] hover:underline">
                            {{ __('ui.view_public_profile') }} &rarr;
                        </a>
                    @endif
                </div>
            @elseif(request('reference'))
                <div role="alert" class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-5 py-4 text-sm">
                    {{ __('ui.no_registration_found', ['reference' => request('reference')]) }}
                </div>
            @endif
        </div>
    </section>
@endsection
