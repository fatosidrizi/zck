@extends('pages.register.shell')

@section('step_intro', __('ui.step_community_intro'))

@section('step_body')
    <form action="{{ route('register.step.store', ['step' => $step]) }}" method="POST">
        @csrf
        @include('pages.register.partials.honeypot')

        <div class="px-6 sm:px-8 py-7 space-y-6">
            <div class="grid sm:grid-cols-2 gap-5">
                <x-form.select name="primary_community"
                               :label="__('ui.primary_community')"
                               :options="$communities"
                               :placeholder="__('ui.select_community')"
                               :value="$data['primary_community'] ?? ''"
                               required />

                <x-form.select name="activity_area"
                               :label="__('ui.activity_area')"
                               :options="$activityAreas"
                               :placeholder="__('ui.select_area')"
                               :value="$data['activity_area'] ?? ''"
                               required />
            </div>

            <fieldset>
                <legend class="block text-sm font-medium text-gray-800 mb-1.5">
                    {{ __('ui.additional_communities') }}
                    <span class="text-gray-400 font-normal">({{ __('ui.optional') }})</span>
                </legend>
                <p class="text-xs text-gray-500 mb-3">{{ __('ui.additional_communities_help') }}</p>

                @php $checked = old('additional_communities', $data['additional_communities'] ?? []); @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" data-community-options>
                    @foreach($communities as $community)
                        <label data-community="{{ $community }}"
                               class="flex items-center gap-2.5 rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-700 cursor-pointer hover:border-[#014DA4]/40 hover:bg-[#014DA4]/[0.03] transition has-checked:border-[#014DA4] has-checked:bg-[#014DA4]/5">
                            <input type="checkbox" name="additional_communities[]" value="{{ $community }}"
                                   @checked(is_array($checked) && in_array($community, $checked))
                                   class="rounded border-gray-300 text-[#014DA4] focus:ring-[#014DA4]">
                            <span>{{ $community }}</span>
                        </label>
                    @endforeach
                </div>
                @error('additional_communities') <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p> @enderror
            </fieldset>
        </div>

        @include('pages.register.partials.nav')
    </form>
@endsection

@push('scripts')
    <script>
        // The primary community is always included — hide it from the "additional" list.
        (function () {
            const primary = document.getElementById('primary_community');
            const options = document.querySelector('[data-community-options]');
            if (!primary || !options) return;

            function sync() {
                options.querySelectorAll('[data-community]').forEach((label) => {
                    const isPrimary = label.dataset.community === primary.value;
                    label.hidden = isPrimary;
                    if (isPrimary) label.querySelector('input').checked = false;
                });
            }

            primary.addEventListener('change', sync);
            sync();
        })();
    </script>
@endpush
