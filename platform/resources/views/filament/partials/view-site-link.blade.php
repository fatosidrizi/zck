{{-- "View site" shortcut in the admin topbar, mirroring the Admin link in the public header. --}}
<x-filament::button
    tag="a"
    :href="route('home')"
    color="gray"
    icon="heroicon-o-globe-alt"
    size="sm"
    outlined
>
    {{ __('ui.view_site') }}
</x-filament::button>
