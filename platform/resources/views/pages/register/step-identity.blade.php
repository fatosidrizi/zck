@extends('pages.register.shell')

@section('step_intro', __('ui.step_identity_intro'))

@section('step_body')
    <form action="{{ route('register.step.store', ['step' => $step]) }}" method="POST">
        @csrf
        @include('pages.register.partials.honeypot')

        <div class="px-6 sm:px-8 py-7 space-y-6">
            <x-form.input name="name"
                          :label="__('ui.org_name')"
                          :value="$data['name'] ?? ''"
                          :help="__('ui.org_name_help')"
                          required />

            <div class="grid sm:grid-cols-3 gap-5">
                <x-form.input name="abbreviation"
                              :label="__('ui.abbreviation')"
                              :value="$data['abbreviation'] ?? ''"
                              placeholder="ZCK"
                              required />

                <div class="sm:col-span-2">
                    <x-form.input name="registration_number"
                                  :label="__('ui.registration_number')"
                                  :value="$data['registration_number'] ?? ''"
                                  :help="__('ui.registration_number_help')"
                                  required />
                </div>
            </div>

            <div class="sm:max-w-sm">
                <x-form.input name="fiscal_number"
                              :label="__('ui.fiscal_number')"
                              :value="$data['fiscal_number'] ?? ''"
                              required />
            </div>
        </div>

        @include('pages.register.partials.nav')
    </form>
@endsection
