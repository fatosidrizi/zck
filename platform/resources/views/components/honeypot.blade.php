{{-- Decoy field: hidden from people and assistive tech, visible to naive bots. --}}
@props(['field'])

<div aria-hidden="true" class="absolute w-px h-px -m-px p-0 overflow-hidden whitespace-nowrap border-0" style="clip: rect(0 0 0 0); clip-path: inset(50%);">
    <label for="{{ $field }}">{{ __('ui.honeypot_label') }}</label>
    <input type="text"
           name="{{ $field }}"
           id="{{ $field }}"
           value=""
           tabindex="-1"
           autocomplete="off">
</div>
