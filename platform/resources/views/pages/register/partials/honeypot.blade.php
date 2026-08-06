{{-- Decoy field: hidden from people and assistive tech, visible to naive bots. --}}
<div aria-hidden="true" class="absolute w-px h-px -m-px p-0 overflow-hidden whitespace-nowrap border-0" style="clip: rect(0 0 0 0); clip-path: inset(50%);">
    <label for="{{ \App\Http\Controllers\RegisterController::HONEYPOT_FIELD }}">{{ __('ui.honeypot_label') }}</label>
    <input type="text"
           name="{{ \App\Http\Controllers\RegisterController::HONEYPOT_FIELD }}"
           id="{{ \App\Http\Controllers\RegisterController::HONEYPOT_FIELD }}"
           value=""
           tabindex="-1"
           autocomplete="off">
</div>
