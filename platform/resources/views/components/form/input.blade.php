@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'value' => '',
    'help' => null,
    'placeholder' => null,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-800 mb-1.5">
        {{ $label }}
        @unless($required)
            <span class="text-gray-400 font-normal">({{ __('ui.optional') }})</span>
        @endunless
    </label>
    <input type="{{ $type }}"
           name="{{ $name }}"
           id="{{ $name }}"
           value="{{ old($name, $value) }}"
           @if($placeholder) placeholder="{{ $placeholder }}" @endif
           @if($required) required @endif
           @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
           {{ $attributes->class([
               'w-full rounded-lg px-4 py-2.5 text-gray-900 border outline-none transition',
               'focus:ring-2 focus:ring-[#014DA4]/30 focus:border-[#014DA4]',
               'border-red-400 bg-red-50/40' => $errors->has($name),
               'border-gray-300 bg-white' => ! $errors->has($name),
           ]) }}>
    @if($help)
        <p class="text-xs text-gray-500 mt-1.5">{{ $help }}</p>
    @endif
    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
    @enderror
</div>
