@extends('pages.register.shell')

@section('step_intro', __('ui.step_review_intro'))

@section('step_body')
    @php
        $sections = [
            1 => [
                'title' => __('ui.step_identity'),
                'rows' => [
                    __('ui.org_name') => $data['name'] ?? null,
                    __('ui.abbreviation') => $data['abbreviation'] ?? null,
                    __('ui.registration_number') => $data['registration_number'] ?? null,
                    __('ui.fiscal_number') => $data['fiscal_number'] ?? null,
                ],
            ],
            2 => [
                'title' => __('ui.step_community'),
                'rows' => [
                    __('ui.primary_community') => $data['primary_community'] ?? null,
                    __('ui.additional_communities') => implode(', ', $data['additional_communities'] ?? []),
                    __('ui.activity_area') => $data['activity_area'] ?? null,
                ],
            ],
            3 => [
                'title' => __('ui.step_contact'),
                'rows' => [
                    __('ui.responsible_person') => $data['responsible_person'] ?? null,
                    __('ui.responsible_person_contact') => $data['responsible_person_contact'] ?? null,
                ],
            ],
        ];
    @endphp

    <div class="px-6 sm:px-8 py-7 space-y-7">
        @foreach($sections as $number => $section)
            <div>
                <div class="flex items-center justify-between gap-4 pb-2 mb-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">{{ $section['title'] }}</h3>
                    <a href="{{ route('register.step', ['step' => $number]) }}"
                       class="text-sm font-medium text-[#014DA4] hover:underline shrink-0">
                        {{ __('ui.edit') }}<span class="sr-only"> — {{ $section['title'] }}</span>
                    </a>
                </div>
                <dl class="grid sm:grid-cols-[200px_minmax(0,1fr)] gap-x-6 gap-y-2.5">
                    @foreach($section['rows'] as $label => $value)
                        <dt class="text-sm text-gray-500">{{ $label }}</dt>
                        <dd class="text-sm mb-2 sm:mb-0 whitespace-pre-line {{ filled($value) ? 'text-gray-900' : 'text-gray-400 italic' }}">
                            {{ filled($value) ? $value : __('ui.not_provided') }}
                        </dd>
                    @endforeach
                </dl>
            </div>
        @endforeach
    </div>

    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        @include('pages.register.partials.honeypot')

        @error('submission')
            <div role="alert" class="mx-6 sm:mx-8 mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                <p class="text-sm text-red-800">{{ $message }}</p>
            </div>
        @enderror

        <div class="px-6 sm:px-8 pb-7">
            <label class="flex gap-3 items-start rounded-lg border px-4 py-4 cursor-pointer transition
                {{ $errors->has('declaration') ? 'border-red-300 bg-red-50/50' : 'border-gray-200 bg-gray-50 hover:border-[#014DA4]/40' }}">
                <input type="checkbox" name="declaration" value="1" required
                       class="mt-0.5 rounded border-gray-300 text-[#014DA4] focus:ring-[#014DA4]">
                <span class="text-sm text-gray-700 leading-relaxed">{{ __('ui.declaration_text') }}</span>
            </label>
            @error('declaration') <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between gap-4 px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 rounded-b-xl">
            <a href="{{ route('register.step', ['step' => 3]) }}"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('ui.back') }}
            </a>

            <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#c8a84e] hover:bg-[#b8942e] text-white font-semibold px-7 py-2.5 rounded-lg transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#c8a84e]/50 focus:ring-offset-2">
                {{ __('ui.submit_registration') }}
            </button>
        </div>
    </form>
@endsection
