@extends('layouts.app')

@section('title', __('ui.report_discrimination'))
@section('meta_description', __('ui.report_subtitle'))

@section('content')
    <section class="bg-[#014DA4] text-white py-10 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-[#c8a84e] text-xs font-semibold tracking-widest uppercase mb-2">{{ __('ui.confidential_report') }}</p>
            <h1 class="text-2xl md:text-4xl font-bold mb-2">{{ __('ui.report_discrimination') }}</h1>
            <p class="text-blue-100 md:text-lg max-w-2xl">{{ __('ui.report_subtitle') }}</p>
        </div>
    </section>

    <section class="py-8 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[248px_minmax(0,1fr)] gap-8 lg:gap-12">

                <aside class="hidden lg:block">
                    <div class="sticky top-8 space-y-6">
                        <div class="px-3">
                            <p class="text-xs font-semibold text-gray-900 mb-1">{{ __('ui.what_happens_next') }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ __('ui.report_next_steps') }}</p>
                        </div>
                        <div class="pt-6 border-t border-gray-200 px-3">
                            <p class="text-xs font-semibold text-gray-900 mb-1">{{ __('ui.need_help') }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed mb-2">{{ __('ui.report_help_text') }}</p>
                            <a href="{{ route('contact') }}" class="text-xs font-medium text-[#014DA4] hover:underline">{{ __('ui.nav_contact') }} &rarr;</a>
                        </div>
                    </div>
                </aside>

                <div class="min-w-0">
                    @if(session('success'))
                        {{-- Confirmation replaces the form; re-showing an empty form here reads as a failed submit. --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-6 sm:px-8 pt-8 pb-6 text-center">
                                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('ui.report_submitted') }}</h2>
                                <p class="text-sm text-gray-500 max-w-md mx-auto">{{ session('success') }}</p>
                            </div>

                            <div class="px-6 sm:px-8 pb-8">
                                <div class="rounded-lg border-2 border-dashed border-[#014DA4]/30 bg-[#014DA4]/5 px-4 py-5 text-center">
                                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-2">{{ __('ui.tracking_code') }}</p>
                                    <p class="text-3xl font-bold text-[#014DA4] tracking-[0.2em] select-all">{{ session('tracking_code') }}</p>
                                    <p class="text-xs text-gray-500 mt-3 max-w-sm mx-auto">{{ __('ui.save_code') }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100">
                                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">&larr; {{ __('ui.nav_home') }}</a>
                                <a href="{{ route('reports.track') }}"
                                   class="inline-flex items-center gap-2 bg-[#014DA4] hover:bg-[#013a7d] text-white font-semibold px-6 py-2.5 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#014DA4]/40 focus:ring-offset-2">
                                    {{ __('ui.track_report') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @else
                        @if($errors->any())
                            <div role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                                <p class="text-sm font-semibold text-red-800">{{ __('ui.fix_errors') }}</p>
                            </div>
                        @endif

                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                            <div class="px-6 sm:px-8 pt-6 sm:pt-8 pb-5 border-b border-gray-100">
                                <h2 class="text-xl font-bold text-gray-900">{{ __('ui.report_form_title') }}</h2>
                                <p class="text-sm text-gray-500 mt-1">{{ __('ui.report_form_intro') }}</p>
                            </div>

                            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-honeypot :field="\App\Http\Controllers\ReportController::HONEYPOT_FIELD" />

                                {{-- Who is reporting --}}
                                <div class="px-6 sm:px-8 py-7 space-y-6">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ __('ui.section_about_you') }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ __('ui.section_about_you_help') }}</p>
                                    </div>

                                    <x-form.input name="reporter_name"
                                                  :label="__('ui.your_name')"
                                                  required />

                                    <div class="grid sm:grid-cols-2 gap-5">
                                        <x-form.input name="reporter_email"
                                                      type="email"
                                                      :label="__('ui.email')"
                                                      :help="__('ui.reporter_email_help')" />

                                        <x-form.input name="reporter_phone"
                                                      type="tel"
                                                      :label="__('ui.phone')" />
                                    </div>
                                </div>

                                {{-- What happened --}}
                                <div class="px-6 sm:px-8 py-7 space-y-6 border-t border-gray-100">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ __('ui.section_incident') }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ __('ui.section_incident_help') }}</p>
                                    </div>

                                    <div class="grid sm:grid-cols-2 gap-5">
                                        <x-form.select name="type"
                                                       :label="__('ui.type_discrimination')"
                                                       :options="__('ui.discrimination_types')"
                                                       :placeholder="__('ui.select_type')"
                                                       required />

                                        <x-form.input name="incident_date"
                                                      type="date"
                                                      :label="__('ui.date_incident')"
                                                      max="{{ now()->toDateString() }}" />
                                    </div>

                                    <x-form.input name="location"
                                                  :label="__('ui.location_incident')"
                                                  :help="__('ui.location_incident_help')" />

                                    <x-form.textarea name="description"
                                                     :label="__('ui.description_incident')"
                                                     :rows="6"
                                                     :help="__('ui.description_incident_help')"
                                                     required />

                                    <x-form.file name="evidence_file"
                                                 :label="__('ui.evidence')"
                                                 accept="image/*,application/pdf,.doc,.docx"
                                                 :help="__('ui.evidence_help')" />
                                </div>

                                <div class="flex items-center justify-between gap-4 px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 rounded-b-xl">
                                    <p class="text-xs text-gray-500 max-w-xs">{{ __('ui.report_privacy_note') }}</p>
                                    <button type="submit"
                                            class="inline-flex shrink-0 items-center gap-2 bg-[#014DA4] hover:bg-[#013a7d] text-white font-semibold px-6 py-2.5 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#014DA4]/40 focus:ring-offset-2">
                                        {{ __('ui.submit_report') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
