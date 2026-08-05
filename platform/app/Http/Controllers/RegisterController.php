<?php

namespace App\Http\Controllers;

use App\Models\Ngo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function create()
    {
        return view('pages.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'registration_number' => 'required|string|max:100',
            'fiscal_number' => 'nullable|string|max:100',
            'primary_community' => 'required|string|max:100',
            'additional_communities' => 'nullable|array',
            'activity_area' => 'required|string|max:255',
            'responsible_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'description' => 'nullable|string|max:5000',
            'logo' => 'nullable|image|max:2048',
        ]);

        $ngoData = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'description' => $validated['description'] ?? null,
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'category' => $validated['activity_area'],
            'is_active' => false, // Pending admin approval
        ];

        if ($request->hasFile('logo')) {
            $ngoData['logo'] = $request->file('logo')->store('ngos/logos', 'public');
        }

        Ngo::create($ngoData);

        return back()->with('success', 'Your organization has been registered successfully! It will be reviewed and activated by our team.');
    }
}
