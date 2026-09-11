<?php

namespace App\Http\Controllers;

use App\Models\Community;

class CommunityController extends Controller
{
    public function index()
    {
        // Sort by the name as shown in the current language, not by the raw
        // JSON column, so the list is alphabetical on every locale.
        $name = fn (Community $community) => $community->translated('name');

        $communities = Community::all();
        $communities = class_exists(\Collator::class)
            ? $communities->sort(fn ($a, $b) => (new \Collator(app()->getLocale()))->compare($name($a), $name($b)))
            : $communities->sortBy($name);

        return view('communities.index', ['communities' => $communities->values()]);
    }

    public function show(string $locale, string $slug)
    {
        $community = Community::where('slug', $slug)->firstOrFail();
        return view('communities.show', compact('community'));
    }
}
