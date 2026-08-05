<?php

namespace App\Http\Controllers;

use App\Models\Community;

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::orderBy('name')->get();
        return view('communities.index', compact('communities'));
    }

    public function show(string $locale, string $slug)
    {
        $community = Community::where('slug', $slug)->firstOrFail();
        return view('communities.show', compact('community'));
    }
}
