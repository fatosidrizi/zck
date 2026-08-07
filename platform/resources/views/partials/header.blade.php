@php
    // Active nav state. Route names are locale-agnostic, so plain patterns work.
    $navLinks = [
        ['route' => 'home', 'match' => ['home'], 'label' => __('ui.nav_home')],
        ['route' => 'about', 'match' => ['about'], 'label' => __('ui.nav_about')],
        ['route' => 'ngos.index', 'match' => ['ngos.*'], 'label' => __('ui.nav_ngos')],
        ['route' => 'communities.index', 'match' => ['communities.*'], 'label' => __('ui.nav_communities')],
        ['route' => 'public-calls.index', 'match' => ['public-calls.*'], 'label' => __('ui.nav_public_calls')],
        ['route' => 'events.index', 'match' => ['events.*'], 'label' => __('ui.nav_events')],
        ['route' => 'contact', 'match' => ['contact'], 'label' => __('ui.nav_contact')],
    ];
@endphp

{{-- Top bar: institutional attribution + language. Social links live in the footer. --}}
<div class="bg-[#014DA4] text-white text-xs py-1.5 border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center gap-4">
        <span class="truncate">{{ __('ui.top_bar') }}</span>
        <x-language-switcher class="shrink-0" />
    </div>
</div>

{{-- Main header --}}
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center gap-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('ui.office_name') }}" class="h-14 w-auto">
                <div>
                    <div class="text-sm font-bold text-[#014DA4] leading-tight">{{ __('ui.platform_line1') }}</div>
                    <div class="text-sm font-bold text-[#014DA4] leading-tight">{{ __('ui.platform_line2') }}</div>
                </div>
            </a>

            <div class="hidden lg:flex items-center gap-3">
                <x-cta action="register" />
                <x-cta action="report" />

                @auth
                    {{-- Account menu: name, admin panel, profile and logout in one place. --}}
                    <div class="relative" id="user-menu-wrap">
                        <button type="button"
                                id="user-menu-btn"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="user-menu"
                                class="flex items-center gap-2 rounded-full pl-1 pr-2 py-1 hover:bg-gray-100 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#014DA4]">
                            <span class="w-8 h-8 rounded-full bg-[#014DA4] text-white text-xs font-bold flex items-center justify-center uppercase" aria-hidden="true">
                                {{ Str::substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="sr-only">{{ __('ui.account_menu') }} &mdash; </span>
                            <span class="text-sm font-medium text-gray-700 max-w-[10rem] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div id="user-menu"
                             role="menu"
                             aria-labelledby="user-menu-btn"
                             class="hidden absolute right-0 top-full mt-1 w-56 bg-white shadow-lg rounded-lg border border-gray-200 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="text-xs text-gray-500">{{ __('ui.signed_in_as') }}</div>
                                <div class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            @if(Auth::user()->isStaff())
                                <a href="{{ \Filament\Facades\Filament::getPanel('admin')->getUrl() }}" role="menuitem" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                                    {{ __('ui.admin_panel') }}
                                </a>
                            @endif
                            <a href="{{ route('profile') }}" role="menuitem" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ __('ui.my_profile') }}
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 mt-1 pt-1">
                                @csrf
                                <button type="submit" role="menuitem" class="w-full flex items-center gap-2 text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    {{ __('ui.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-[#014DA4] px-2 py-2 transition">{{ __('ui.login') }}</a>
                @endauth
            </div>

            {{-- Mobile: hamburger only; the switcher lives in the top bar on every breakpoint. --}}
            <div class="lg:hidden flex items-center space-x-2">
                <button id="mobile-menu-btn" type="button" aria-expanded="false" aria-controls="mobile-menu" class="p-2 rounded hover:bg-gray-100">
                    <span class="sr-only">{{ __('ui.menu') }}</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Navigation bar --}}
    <nav class="hidden lg:block bg-[#014DA4] border-b-4 border-[#013b7a]" aria-label="{{ __('ui.nav_primary') }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-1">
                @foreach($navLinks as $link)
                    @php $isActive = request()->routeIs(...$link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       @if($isActive) aria-current="page" @endif
                       @class([
                           'text-white text-sm font-medium px-4 py-3 transition border-b-[3px] -mb-[3px]',
                           'border-[#c8a84e] bg-[#013b7a]' => $isActive,
                           'border-transparent hover:bg-[#013b7a]' => ! $isActive,
                       ])>{{ $link['label'] }}</a>

                    {{-- News sits between NGOs and Communities and carries a submenu. --}}
                    @if($link['route'] === 'ngos.index')
                        @php $newsActive = request()->routeIs('news.*'); @endphp
                        <div class="relative group">
                            <button type="button"
                                    @if($newsActive) aria-current="page" @endif
                                    @class([
                                        'text-white text-sm font-medium px-4 py-3 transition flex items-center border-b-[3px] -mb-[3px]',
                                        'border-[#c8a84e] bg-[#013b7a]' => $newsActive,
                                        'border-transparent hover:bg-[#013b7a]' => ! $newsActive,
                                    ])>
                                {{ __('ui.nav_news') }}
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 top-full bg-white shadow-lg rounded-b-lg min-w-[180px] hidden group-hover:block group-focus-within:block z-50">
                                <a href="{{ route('news.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">{{ __('ui.nav_all_news') }}</a>
                                <a href="{{ route('news.index', ['category' => 'bulletin']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">{{ __('ui.nav_bulletins') }}</a>
                                <a href="{{ route('news.index', ['category' => 'report']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">{{ __('ui.nav_reports') }}</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </nav>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-1">
            @foreach($navLinks as $link)
                @php $isActive = request()->routeIs(...$link['match']); @endphp
                <a href="{{ route($link['route']) }}"
                   @if($isActive) aria-current="page" @endif
                   @class([
                       'block text-sm font-medium py-2 px-3 rounded',
                       'bg-blue-50 text-[#014DA4] font-semibold' => $isActive,
                       'text-gray-700 hover:bg-gray-50' => ! $isActive,
                   ])>{{ $link['label'] }}</a>

                @if($link['route'] === 'ngos.index')
                    @php $newsActive = request()->routeIs('news.*'); @endphp
                    <a href="{{ route('news.index') }}"
                       @if($newsActive) aria-current="page" @endif
                       @class([
                           'block text-sm font-medium py-2 px-3 rounded',
                           'bg-blue-50 text-[#014DA4] font-semibold' => $newsActive,
                           'text-gray-700 hover:bg-gray-50' => ! $newsActive,
                       ])>{{ __('ui.nav_news') }}</a>
                @endif
            @endforeach

            <div class="pt-2 space-y-2 border-t border-gray-100 mt-2">
                @auth
                    <div class="px-3 pt-2 pb-1">
                        <div class="text-xs text-gray-500">{{ __('ui.signed_in_as') }}</div>
                        <div class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</div>
                    </div>
                    @if(Auth::user()->isStaff())
                        <a href="{{ \Filament\Facades\Filament::getPanel('admin')->getUrl() }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.admin_panel') }}</a>
                    @endif
                    <a href="{{ route('profile') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.my_profile') }}</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left text-sm font-medium text-red-600 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.login') }}</a>
                    <a href="{{ route('user.register') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.signup') }}</a>
                @endauth
                <div class="space-y-2 pt-1">
                    <x-cta action="register" block />
                    <x-cta action="report" block />
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    (function () {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        menuBtn.addEventListener('click', function () {
            const open = mobileMenu.classList.toggle('hidden') === false;
            menuBtn.setAttribute('aria-expanded', String(open));
        });

        const userBtn = document.getElementById('user-menu-btn');
        const userMenu = document.getElementById('user-menu');
        if (!userBtn || !userMenu) return;

        const setOpen = (open) => {
            userMenu.classList.toggle('hidden', !open);
            userBtn.setAttribute('aria-expanded', String(open));
        };

        userBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            setOpen(userMenu.classList.contains('hidden'));
        });
        document.addEventListener('click', function (e) {
            if (!document.getElementById('user-menu-wrap').contains(e.target)) setOpen(false);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !userMenu.classList.contains('hidden')) {
                setOpen(false);
                userBtn.focus();
            }
        });
    })();
</script>
