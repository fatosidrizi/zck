<?php

namespace App\Http\Controllers;

use App\Models\Ngo;
use Illuminate\Http\Request;

class NgoController extends Controller
{
    public function index(Request $request)
    {
        $ngos = Ngo::where('is_active', true)
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('ngos.index', compact('ngos'));
    }

    public function show(string $slug)
    {
        $ngo = Ngo::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('ngos.show', compact('ngo'));
    }
}
