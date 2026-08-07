<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
