<?php

namespace App\Http\Controllers;

use App\Models\DiscriminationReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Decoy field. A real person never sees it, so any value means a bot.
     */
    public const HONEYPOT_FIELD = 'incident_reference';

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        if (filled($request->input(self::HONEYPOT_FIELD))) {
            Log::warning('Discrimination report blocked as automated', [
                'reason' => 'honeypot',
                'ip' => $request->ip(),
            ]);

            // Look identical to success so the bot learns nothing.
            return redirect()->route('reports.create')
                ->with('success', __('ui.report_success'))
                ->with('tracking_code', '—');
        }

        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
            'reporter_phone' => 'nullable|string|max:50',
            'type' => 'required|string|max:100|in:' . implode(',', array_keys(self::types())),
            'description' => 'required|string|max:10000',
            'location' => 'nullable|string|max:255',
            'incident_date' => 'nullable|date|before_or_equal:today',
            'evidence_file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('evidence_file')) {
            $validated['evidence_file'] = $request->file('evidence_file')->store('reports/evidence', 'public');
        }

        $report = DiscriminationReport::create($validated);

        return redirect()->route('reports.create')
            ->with('success', __('ui.report_success'))
            ->with('tracking_code', $report->tracking_code);
    }

    /**
     * Stored keys are locale-independent; only the labels are translated.
     *
     * @return array<string, string>
     */
    public static function types(): array
    {
        return __('ui.discrimination_types');
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
