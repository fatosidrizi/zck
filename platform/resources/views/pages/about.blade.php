@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">About Us</h1>
            <p class="text-blue-100 text-lg">Office for Community Issues - Prime Minister's Office</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed">
                    The Office for Community Issues operates within the Prime Minister's Office since 2008. Our mission is to mobilize
                    and organize citizens through strategic planning to solve community problems. We work at both central and local
                    government levels, coordinating efforts across government agencies, independent institutions, and international organizations.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h2>
                <p class="text-gray-600 leading-relaxed">
                    Unity in Diversity - empowering minorities and promoting democratic values for all communities in Kosovo,
                    ensuring justice, solidarity, and equality regardless of community background.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Mandate</h2>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-[#014DA4] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Informing leadership about pressing community issues
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-[#014DA4] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Coordinating government work on minority rights
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-[#014DA4] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Analyzing and advising on policies affecting community interests
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-[#014DA4] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Ensuring effective resource allocation across Kosovo
                    </li>
                </ul>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                    <div class="w-14 h-14 bg-[#014DA4] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Campaigns</h3>
                    <p class="text-sm text-gray-500">Awareness initiatives addressing discrimination and human rights violations</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                    <div class="w-14 h-14 bg-[#c8a84e] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Empowerment</h3>
                    <p class="text-sm text-gray-500">Training, education, and leadership development for minority communities</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                    <div class="w-14 h-14 bg-[#014DA4] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Collaboration</h3>
                    <p class="text-sm text-gray-500">Forming alliances with government bodies, NGOs, and private sector</p>
                </div>
            </div>
        </div>
    </section>
@endsection
