@extends('layouts.app')

@section('title', 'Public Calls')

@section('content')
    <section class="bg-[#014DA4] text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Public Calls</h1>
            <p class="text-blue-100 text-lg">Grants, recruitment, and funding opportunities</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('public-calls.index') }}" class="px-4 py-2 rounded text-sm font-medium {{ !request('type') ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">All</a>
                <a href="{{ route('public-calls.index', ['type' => 'recruitment']) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('type') === 'recruitment' ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Recruitment</a>
                <a href="{{ route('public-calls.index', ['type' => 'grant']) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('type') === 'grant' ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Grants</a>
                <a href="{{ route('public-calls.index', ['type' => 'funding']) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('type') === 'funding' ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Funding</a>
                <a href="{{ route('public-calls.index', ['type' => 'commission']) }}" class="px-4 py-2 rounded text-sm font-medium {{ request('type') === 'commission' ? 'bg-[#014DA4] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Commissions</a>
            </div>

            <div class="space-y-3">
                @forelse($calls as $call)
                    <div class="flex items-center justify-between p-5 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div>
                            <span class="inline-block px-2 py-0.5 text-xs font-medium bg-[#c8a84e]/10 text-[#c8a84e] rounded mb-1">{{ ucfirst($call->type) }}</span>
                            <h3 class="font-semibold text-gray-900">
                                <a href="{{ route('public-calls.show', $call->slug) }}" class="hover:text-[#014DA4] transition">{{ $call->title }}</a>
                            </h3>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            @if($call->deadline)
                                <div class="text-sm font-medium {{ $call->isOpen() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $call->isOpen() ? 'Open' : 'Closed' }}
                                </div>
                                <div class="text-xs text-gray-400">{{ $call->deadline->format('M d, Y') }}</div>
                            @else
                                <div class="text-sm font-medium text-green-600">Open</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No public calls found.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $calls->links() }}</div>
        </div>
    </section>
@endsection
