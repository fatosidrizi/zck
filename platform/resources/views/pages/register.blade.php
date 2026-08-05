@extends('layouts.app')

@section('title', 'Register Your Organization')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Register Your Organization</h1>
            <p class="text-blue-100 text-lg">Join the platform and connect with communities across Kosovo</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h3 class="text-green-800 font-semibold text-lg mb-2">Registration Submitted</h3>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                @csrf

                <h2 class="text-lg font-bold text-gray-900 mb-5 pb-3 border-b border-gray-200">Organization Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Organization Name *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="abbreviation" class="block text-sm font-medium text-gray-700 mb-1">Abbreviation</label>
                        <input type="text" name="abbreviation" id="abbreviation" value="{{ old('abbreviation') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('abbreviation') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-1">Registration Number *</label>
                        <input type="text" name="registration_number" id="registration_number" required value="{{ old('registration_number') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('registration_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="fiscal_number" class="block text-sm font-medium text-gray-700 mb-1">Fiscal Number</label>
                        <input type="text" name="fiscal_number" id="fiscal_number" value="{{ old('fiscal_number') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('fiscal_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <h2 class="text-lg font-bold text-gray-900 mb-5 pb-3 border-b border-gray-200 mt-8">Community & Activity</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label for="primary_community" class="block text-sm font-medium text-gray-700 mb-1">Primary Community *</label>
                        <select name="primary_community" id="primary_community" required
                                class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                            <option value="">Select community...</option>
                            @foreach(['Ashkali', 'Bosniak', 'Egyptian', 'Goran', 'Croat', 'Montenegrin', 'Roma', 'Serb', 'Turkish'] as $community)
                                <option value="{{ $community }}" {{ old('primary_community') === $community ? 'selected' : '' }}>{{ $community }}</option>
                            @endforeach
                        </select>
                        @error('primary_community') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="activity_area" class="block text-sm font-medium text-gray-700 mb-1">Area of Activity *</label>
                        <select name="activity_area" id="activity_area" required
                                class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                            <option value="">Select area...</option>
                            @foreach(['Human Rights', 'Anti-Discrimination', 'Anti-Bullying', 'Education', 'Health', 'Culture', 'Youth', 'Environment', 'Economic Development', 'Legal Aid', 'Media', 'Other'] as $area)
                                <option value="{{ $area }}" {{ old('activity_area') === $area ? 'selected' : '' }}>{{ $area }}</option>
                            @endforeach
                        </select>
                        @error('activity_area') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Communities</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach(['Ashkali', 'Bosniak', 'Egyptian', 'Goran', 'Croat', 'Montenegrin', 'Roma', 'Serb', 'Turkish'] as $community)
                            <label class="flex items-center space-x-2 text-sm text-gray-600">
                                <input type="checkbox" name="additional_communities[]" value="{{ $community }}"
                                       {{ is_array(old('additional_communities')) && in_array($community, old('additional_communities')) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#014DA4] focus:ring-[#014DA4]">
                                <span>{{ $community }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <h2 class="text-lg font-bold text-gray-900 mb-5 pb-3 border-b border-gray-200 mt-8">Contact Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div class="md:col-span-2">
                        <label for="responsible_person" class="block text-sm font-medium text-gray-700 mb-1">Responsible Person *</label>
                        <input type="text" name="responsible_person" id="responsible_person" required value="{{ old('responsible_person') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('responsible_person') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="contact_email" id="contact_email" required value="{{ old('contact_email') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('contact_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                        <input type="text" name="contact_phone" id="contact_phone" required value="{{ old('contact_phone') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('contact_phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Organization Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-8">
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Organization Logo</label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                    <p class="text-xs text-gray-400 mt-1">Upload your organization's logo (max 2MB, PNG/JPG)</p>
                    @error('logo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-3 rounded transition">
                    Submit Registration
                </button>
            </form>
        </div>
    </section>
@endsection
