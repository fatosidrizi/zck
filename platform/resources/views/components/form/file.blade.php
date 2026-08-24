@props([
    'name',
    'label',
    'required' => false,
    'help' => null,
    'accept' => null,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-800 mb-1.5">
        {{ $label }}
        @unless($required)
            <span class="text-gray-400 font-normal">({{ __('ui.optional') }})</span>
        @endunless
    </label>
    <input type="file"
           name="{{ $name }}"
           id="{{ $name }}"
           @if($accept) accept="{{ $accept }}" @endif
           @if($required) required @endif
           @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
           {{ $attributes->class([
               'w-full rounded-lg text-sm text-gray-600 border outline-none transition',
               'file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-medium',
               'file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 file:cursor-pointer',
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
