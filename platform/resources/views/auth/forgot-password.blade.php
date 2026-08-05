@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Reset Password</h1>
            <p class="text-blue-100 text-lg">Enter your email to receive a password reset link</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">{{ session('status') }}</div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-3 rounded transition">Send Reset Link</button>
                <p class="text-center text-sm text-gray-500"><a href="{{ route('login') }}" class="text-[#014DA4] font-medium">Back to Login</a></p>
            </form>
        </div>
    </section>
@endsection
