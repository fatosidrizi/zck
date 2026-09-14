{{-- Cloudflare Turnstile widget. Renders nothing when the keys are not configured. --}}
@if(\App\Rules\Turnstile::enabled())
    <div>
        <div class="cf-turnstile"
             data-sitekey="{{ config('services.turnstile.site_key') }}"
             data-language="{{ app()->getLocale() }}"
             data-theme="light"></div>
        @error(\App\Rules\Turnstile::FIELD) <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    @once
        @push('scripts')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endpush
    @endonce
@endif
