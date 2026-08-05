<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $communities = Community::orderBy('name')->get();

        $upcoming = Event::with('community')
            ->where('event_date', '>=', now()->toDateString())
            ->when($request->community, fn($q, $id) => $q->where('community_id', $id))
            ->orderBy('event_date')
            ->get();

        $past = Event::with('community')
            ->where('event_date', '<', now()->toDateString())
            ->when($request->community, fn($q, $id) => $q->where('community_id', $id))
            ->orderByDesc('event_date')
            ->paginate(12)
            ->withQueryString();

        // Calendar data: all events grouped by date for the current month
        $calendarMonth = $request->month ? \Carbon\Carbon::parse($request->month . '-01') : now();
        $calendarEvents = Event::whereBetween('event_date', [
            $calendarMonth->copy()->startOfMonth(),
            $calendarMonth->copy()->endOfMonth(),
        ])->get()->groupBy(fn($e) => $e->event_date->format('Y-m-d'));

        return view('events.index', compact('upcoming', 'past', 'communities', 'calendarEvents', 'calendarMonth'));
    }

    public function show(string $slug)
    {
        $event = Event::with('community')->where('slug', $slug)->firstOrFail();
        return view('events.show', compact('event'));
    }
}
