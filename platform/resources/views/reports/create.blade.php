@extends('layouts.app')

@section('title', 'Report Discrimination')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Report Discrimination</h1>
            <p class="text-blue-100 text-lg">Your report will be handled confidentially. You will receive a tracking code to follow up.</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h3 class="text-green-800 font-semibold text-lg mb-2">Report Submitted Successfully</h3>
                    <p class="text-green-700 mb-3">{{ session('success') }}</p>
                    <div class="bg-white border border-green-300 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500 mb-1">Your Tracking Code:</p>
                        <p class="text-2xl font-bold text-green-800">{{ session('tracking_code') }}</p>
                        <p class="text-sm text-gray-500 mt-2">Save this code to check the status of your report.</p>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('reports.track') }}" class="text-green-700 hover:text-green-800 font-medium">Track Your Report &rarr;</a>
                    </div>
                </div>
            @endif

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="reporter_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name *</label>
                        <input type="text" name="reporter_name" id="reporter_name" required value="{{ old('reporter_name') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('reporter_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="reporter_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="reporter_email" id="reporter_email" value="{{ old('reporter_email') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('reporter_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="reporter_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="reporter_phone" id="reporter_phone" value="{{ old('reporter_phone') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('reporter_phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type of Discrimination *</label>
                        <select name="type" id="type" required
                                class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                            <option value="">Select type...</option>
                            @foreach(['racial' => 'Racial', 'ethnic' => 'Ethnic', 'religious' => 'Religious', 'language' => 'Language-based', 'gender' => 'Gender-based', 'disability' => 'Disability-based', 'other' => 'Other'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location of Incident</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('location') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="incident_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Incident</label>
                        <input type="date" name="incident_date" id="incident_date" value="{{ old('incident_date') }}"
                               class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                        @error('incident_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description of Incident *</label>
                    <textarea name="description" id="description" rows="6" required
                              class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="evidence_file" class="block text-sm font-medium text-gray-700 mb-1">Evidence (optional)</label>
                    <input type="file" name="evidence_file" id="evidence_file"
                           class="w-full border border-gray-300 rounded px-4 py-2.5 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Upload photos, documents, or other evidence (max 10MB)</p>
                    @error('evidence_file') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-3 rounded transition">
                    Submit Report
                </button>
            </form>
        </div>
    </section>
@endsection
