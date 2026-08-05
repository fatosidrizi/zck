@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Contact Us</h1>
            <p class="text-blue-100 text-lg">Get in touch with the Office for Community Issues</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Send us a message</h2>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
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
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input type="text" name="subject" id="subject" required value="{{ old('subject') }}"
                                   class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">
                            @error('subject') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea name="message" id="message" rows="5" required
                                      class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none">{{ old('message') }}</textarea>
                            @error('message') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-3 rounded transition">
                            Send Message
                        </button>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Office Information</h2>
                        <div class="space-y-4 text-gray-600">
                            <div>
                                <div class="font-medium text-gray-900">Address</div>
                                <p>Ndertesa e Qeverise, Bulevardi Nene Tereza</p>
                                <p>Prishtine, Republika e Kosoves</p>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Email</div>
                                <p>info@zck.rks-gov.net</p>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Working Hours</div>
                                <p>Monday - Friday, 08:00 - 16:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
