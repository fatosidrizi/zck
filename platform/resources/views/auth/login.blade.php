@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Login</h1>
            <p class="text-blue-100 text-lg">Access your account</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">{{ session('status') }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" autofocus
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#014DA4] focus:ring-[#014DA4]">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-[#014DA4] hover:text-[#013b7a]">Forgot password?</a>
                </div>
                <button type="submit" class="w-full bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-3 rounded transition">Login</button>
                <p class="text-center text-sm text-gray-500">Don't have an account? <a href="{{ route('user.register') }}" class="text-[#014DA4] font-medium">Sign up</a></p>
            </form>
        </div>
    </section>
@endsection
