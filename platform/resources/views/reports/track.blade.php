@extends('layouts.app')

@section('title', 'Track Your Report')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Track Your Report</h1>
            <p class="text-blue-100 text-lg">Enter your tracking code to check the status of your discrimination report</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('reports.track') }}" method="GET" class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 mb-8">
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Tracking Code</label>
                    <input type="text" name="code" id="code" value="{{ request('code') }}" placeholder="e.g. ZCK-AB12CD34" required
                           class="w-full border border-gray-300 rounded px-4 py-2.5 focus:ring-2 focus:ring-[#014DA4] focus:border-[#014DA4] outline-none uppercase">
                </div>
                <button type="submit" class="w-full bg-[#32373c] hover:bg-[#23282d] text-white font-semibold py-3 rounded transition">
                    Check Status
                </button>
            </form>

            @if(isset($report))
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Report Status</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tracking Code:</span>
                            <span class="font-medium">{{ $report->tracking_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Type:</span>
                            <span class="font-medium">{{ ucfirst($report->type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Submitted:</span>
                            <span class="font-medium">{{ $report->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status:</span>
                            <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                                @if($report->status === 'new') bg-yellow-100 text-yellow-800
                                @elseif($report->status === 'in_review') bg-blue-100 text-blue-800
                                @elseif($report->status === 'resolved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($report->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            @elseif(request('code'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg">
                    No report found with tracking code "{{ request('code') }}". Please check the code and try again.
                </div>
            @endif
        </div>
    </section>
@endsection
