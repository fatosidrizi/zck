@props([
    'name',
    'label',
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'value' => '',
    'help' => null,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-800 mb-1.5">
        {{ $label }}
        @unless($required)
            <span class="text-gray-400 font-normal">({{ __('ui.optional') }})</span>
        @endunless
    </label>
    <select name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            {{ $attributes->class([
                'w-full rounded-lg px-4 py-2.5 text-gray-900 border outline-none transition appearance-none bg-no-repeat',
                'focus:ring-2 focus:ring-[#014DA4]/30 focus:border-[#014DA4]',
                'border-red-400 bg-red-50/40' => $errors->has($name),
                'border-gray-300 bg-white' => ! $errors->has($name),
            ]) }}>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{-- A list means the label is its own value; an associative array maps value => label. --}}
        @foreach($options as $optionValue => $optionLabel)
            @php $optionValue = is_int($optionValue) ? $optionLabel : $optionValue; @endphp
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @if($help)
        <p class="text-xs text-gray-500 mt-1.5">{{ $help }}</p>
    @endif
    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
    @enderror
</div>
