{{-- Top bar --}}
<div class="bg-[#014DA4] text-white text-xs py-1.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <span>{{ __('ui.top_bar') }}</span>
        <div class="hidden sm:flex items-center space-x-3">
            {{-- Language switcher --}}
            @php
    $otherLocale = app()->getLocale() === 'en' ? 'sq' : 'en';
    $currentPath = request()->path();
    $switchedPath = preg_replace('#^(' . app()->getLocale() . ')(/|$)#', $otherLocale . '$2', $currentPath);
@endphp
            <a href="{{ url($switchedPath) }}"
               class="font-semibold hover:text-[#c8a84e] transition px-1.5 py-0.5 border border-white/30 rounded text-[10px] uppercase">
                {{ $otherLocale === 'sq' ? 'SQ' : 'EN' }}
            </a>
            <span class="text-white/30">|</span>
            <a href="https://www.facebook.com" target="_blank" rel="noopener" class="hover:text-[#c8a84e] transition">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://twitter.com" target="_blank" rel="noopener" class="hover:text-[#c8a84e] transition">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
            </a>
            <a href="https://instagram.com" target="_blank" rel="noopener" class="hover:text-[#c8a84e] transition">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
            <a href="https://youtube.com" target="_blank" rel="noopener" class="hover:text-[#c8a84e] transition">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Main header --}}
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-3">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Kosovo Coat of Arms" class="h-14 w-auto">
                <div>
                    <div class="text-sm font-bold text-[#014DA4] leading-tight">{{ __('ui.platform_line1') }}</div>
                    <div class="text-sm font-bold text-[#014DA4] leading-tight">{{ __('ui.platform_line2') }}</div>
                    <div class="text-[10px] text-gray-500 leading-tight mt-0.5">{{ __('ui.office_name') }} - {{ __('ui.office_subtitle') }}</div>
                </div>
            </a>

            <div class="hidden lg:flex items-center space-x-2">
                @auth
                    <a href="{{ route('profile') }}" class="text-sm font-medium text-gray-700 hover:text-[#014DA4] px-3 py-2 transition">{{ Auth::user()->name }}</a>
                    @if(Auth::user()->isStaff())
                        <a href="/admin" class="text-sm font-medium text-gray-700 hover:text-[#014DA4] px-3 py-2 transition">{{ __('ui.admin') }}</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-600 px-3 py-2 transition">{{ __('ui.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-[#014DA4] px-3 py-2 transition">{{ __('ui.login') }}</a>
                @endauth
                <a href="{{ route('register') }}" class="bg-[#32373c] hover:bg-[#23282d] text-white text-sm font-medium px-5 py-2 rounded transition">{{ __('ui.register_ngo') }}</a>
                <a href="{{ route('reports.create') }}" class="bg-[#014DA4] hover:bg-[#013b7a] text-white text-sm font-medium px-5 py-2 rounded transition">{{ __('ui.report_discrimination') }}</a>
            </div>

            {{-- Mobile: language switcher + hamburger --}}
            <div class="lg:hidden flex items-center space-x-2">
                <a href="{{ url($switchedPath) }}"
                   class="text-[10px] font-bold text-[#014DA4] border border-[#014DA4] rounded px-2 py-1 uppercase">
                    {{ $otherLocale === 'sq' ? 'SQ' : 'EN' }}
                </a>
                <button id="mobile-menu-btn" class="p-2 rounded hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Navigation bar --}}
    <nav class="hidden lg:block bg-[#014DA4]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-1">
                <a href="{{ route('home') }}" class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition">{{ __('ui.nav_home') }}</a>
                <a href="{{ route('about') }}" class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition">{{ __('ui.nav_about') }}</a>
                <a href="{{ route('ngos.index') }}" class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition">{{ __('ui.nav_ngos') }}</a>
                <div class="relative group">
                    <button class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition flex items-center">
                        {{ __('ui.nav_news') }}
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute left-0 top-full bg-white shadow-lg rounded-b-lg min-w-[180px] hidden group-hover:block z-50">
                        <a href="{{ route('news.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">{{ __('ui.nav_all_news') }}</a>
                        <a href="{{ route('news.index', ['category' => 'bulletin']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">{{ __('ui.nav_bulletins') }}</a>
                        <a href="{{ route('news.index', ['category' => 'report']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#014DA4]">{{ __('ui.nav_reports') }}</a>
                    </div>
                </div>
                <a href="{{ route('communities.index') }}" class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition">{{ __('ui.nav_communities') }}</a>
                <a href="{{ route('public-calls.index') }}" class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition">{{ __('ui.nav_public_calls') }}</a>
                <a href="{{ route('events.index') }}" class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition">{{ __('ui.nav_events') }}</a>
                <a href="{{ route('contact') }}" class="text-white text-sm font-medium px-4 py-3 hover:bg-[#013b7a] transition">{{ __('ui.nav_contact') }}</a>
            </div>
        </div>
    </nav>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_home') }}</a>
            <a href="{{ route('about') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_about') }}</a>
            <a href="{{ route('ngos.index') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_ngos') }}</a>
            <a href="{{ route('news.index') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_news') }}</a>
            <a href="{{ route('communities.index') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_communities') }}</a>
            <a href="{{ route('public-calls.index') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_public_calls') }}</a>
            <a href="{{ route('events.index') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_events') }}</a>
            <a href="{{ route('contact') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.nav_contact') }}</a>
            <div class="pt-2 space-y-2">
                @auth
                    <a href="{{ route('profile') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.my_profile') }}</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left text-sm font-medium text-red-600 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.login') }}</a>
                    <a href="{{ route('user.register') }}" class="block text-sm font-medium text-gray-700 py-2 px-3 rounded hover:bg-gray-50">{{ __('ui.signup') }}</a>
                @endauth
                <a href="{{ route('register') }}" class="block text-sm font-medium text-white bg-[#32373c] text-center px-4 py-2.5 rounded">{{ __('ui.register_ngo') }}</a>
                <a href="{{ route('reports.create') }}" class="block text-sm font-medium text-white bg-[#014DA4] text-center px-4 py-2.5 rounded">{{ __('ui.report_discrimination') }}</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
