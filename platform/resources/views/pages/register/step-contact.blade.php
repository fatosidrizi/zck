@extends('pages.register.shell')

@section('step_intro', __('ui.step_contact_intro'))

@section('step_body')
    <form action="{{ route('register.step.store', ['step' => $step]) }}" method="POST">
        @csrf
        @include('pages.register.partials.honeypot')

        <div class="px-6 sm:px-8 py-7 space-y-6">
            <div class="sm:max-w-md">
                <x-form.input name="responsible_person"
                              :label="__('ui.responsible_person')"
                              :value="$data['responsible_person'] ?? ''"
                              :help="__('ui.responsible_person_help')"
                              required />
            </div>

            <x-form.textarea name="responsible_person_contact"
                             :label="__('ui.responsible_person_contact')"
                             :value="$data['responsible_person_contact'] ?? ''"
                             :help="__('ui.responsible_person_contact_help')"
                             rows="4"
                             required />

            {{-- Transparency obligation: say plainly what happens to these details. --}}
            <div class="flex gap-2.5 rounded-lg bg-gray-50 border border-gray-200 px-4 py-3.5">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <div class="text-xs text-gray-600 leading-relaxed">
                    <p class="font-semibold text-gray-800 mb-1">{{ __('ui.privacy_notice_title') }}</p>
                    <p>{{ __('ui.privacy_notice_body') }}</p>
                </div>
            </div>
        </div>

        @include('pages.register.partials.nav')
    </form>
@endsection
