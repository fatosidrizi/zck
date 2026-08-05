<?php

namespace App\Http\Controllers;

use App\Models\DiscriminationReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
            'reporter_phone' => 'nullable|string|max:50',
            'type' => 'required|string|max:100',
            'description' => 'required|string|max:10000',
            'location' => 'nullable|string|max:255',
            'incident_date' => 'nullable|date|before_or_equal:today',
            'evidence_file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('evidence_file')) {
            $validated['evidence_file'] = $request->file('evidence_file')->store('reports/evidence', 'public');
        }

        $report = DiscriminationReport::create($validated);

        return back()
            ->with('success', 'Your report has been submitted successfully.')
            ->with('tracking_code', $report->tracking_code);
    }

    public function track(Request $request)
    {
        $report = null;

        if ($request->code) {
            $report = DiscriminationReport::where('tracking_code', strtoupper($request->code))->first();
        }

        return view('reports.track', compact('report'));
    }
}
