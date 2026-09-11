<?php

namespace App\Http\Controllers;

use App\Models\PublicCall;
use Illuminate\Http\Request;

class PublicCallController extends Controller
{
    public function index(Request $request)
    {
        $calls = PublicCall::published()
            ->withTranslation('title')
            ->when($request->type, fn($q, $type) => $q->where('type', $type))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('public-calls.index', compact('calls'));
    }

    public function show(string $locale, string $slug)
    {
        $call = PublicCall::published()->where('slug', $slug)->firstOrFail();
        return view('public-calls.show', compact('call'));
    }
}
