@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">My Profile</h1>
            <p class="text-blue-100 text-lg">Manage your account settings</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Profile Info --}}
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-bold text-gray-900 mb-5 pb-3 border-b border-gray-200">Profile Information</h2>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-5">{{ session('success') }}</div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <p class="text-gray-600 capitalize">{{ str_replace('_', ' ', $user->role) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                        <p class="text-gray-600">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
                <button type="submit" class="mt-6 bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-2.5 px-6 rounded transition">Save Changes</button>
            </form>

            {{-- Change Password --}}
            <form action="{{ route('profile.password') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-bold text-gray-900 mb-5 pb-3 border-b border-gray-200">Change Password</h2>

                @if(session('password_success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-5">{{ session('password_success') }}</div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('current_password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" id="new_password" required
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    </div>
                </div>
                <button type="submit" class="mt-6 bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-2.5 px-6 rounded transition">Change Password</button>
            </form>
        </div>
    </section>
@endsection
