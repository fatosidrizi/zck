@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Create Account</h1>
            <p class="text-blue-100 text-lg">Sign up for a personal account</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('user.register.post') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                </div>
                <button type="submit" class="w-full bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-3 rounded transition">Create Account</button>
                <p class="text-center text-sm text-gray-500">Already have an account? <a href="{{ route('login') }}" class="text-[#014DA4] font-medium">Login</a></p>
            </form>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">Want to register your organization instead?</p>
                <a href="{{ route('register') }}" class="text-[#014DA4] font-medium text-sm">Register NGO &rarr;</a>
            </div>
        </div>
    </section>
@endsection
