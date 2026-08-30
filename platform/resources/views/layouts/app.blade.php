<!DOCTYPE html>
<html lang="{{ \App\Http\Middleware\SetLocale::languageTag(app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ZCK Platform') - Office for Community Issues</title>
    <meta name="description" content="@yield('meta_description', 'Platform of the Office for Community Issues - Prime Minister Office, Kosovo')">
    <meta property="og:title" content="@yield('title', 'ZCK Platform') - Office for Community Issues">
    <meta property="og:description" content="@yield('meta_description', 'Platform of the Office for Community Issues - Prime Minister Office, Kosovo')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    {{-- Localized alternates: tells search engines these URLs are the same page
         in different languages, rather than duplicate content competing. --}}
    @php
        $currentLocale = app()->getLocale();
        $localePath = request()->path();
        $localeQuery = request()->getQueryString();
    @endphp
    @foreach(\App\Http\Middleware\SetLocale::SUPPORTED_LOCALES as $altLocale)
        @php
            $altPath = preg_replace(
                '#^(' . preg_quote($currentLocale, '#') . ')(/|$)#',
                $altLocale . '$2',
                $localePath
            );
            $altUrl = url($altPath) . ($localeQuery ? '?' . $localeQuery : '');
        @endphp
        <link rel="alternate" hreflang="{{ \App\Http\Middleware\SetLocale::languageTag($altLocale) }}" href="{{ $altUrl }}">
        @if($altLocale === config('app.locale'))
            <link rel="alternate" hreflang="x-default" href="{{ $altUrl }}">
        @endif
    @endforeach
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gray-50 text-gray-900">

    @include('partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
