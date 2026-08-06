<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Event;
use App\Models\News;
use App\Models\Ngo;
use App\Models\PublicCall;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $stats = [
            'ngos' => Ngo::published()->count(),
            'communities' => Community::count(),
            'donors' => 0,
            'events' => Event::count(),
        ];

        $latestNews = News::published()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $latestCalls = PublicCall::published()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $activeNgos = Ngo::published()
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('pages.home', compact('stats', 'latestNews', 'latestCalls', 'activeNgos'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSend(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        return back()->with('success', 'Your message has been sent. We will get back to you soon.');
    }

    public function sitemap()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $static = [
            ['route' => 'home', 'freq' => 'daily', 'priority' => '1.0'],
            ['route' => 'about', 'freq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'contact', 'freq' => 'monthly', 'priority' => '0.6'],
            ['route' => 'news.index', 'freq' => 'daily', 'priority' => '0.9'],
            ['route' => 'public-calls.index', 'freq' => 'weekly', 'priority' => '0.8'],
            ['route' => 'ngos.index', 'freq' => 'weekly', 'priority' => '0.8'],
            ['route' => 'communities.index', 'freq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'events.index', 'freq' => 'weekly', 'priority' => '0.8'],
            ['route' => 'reports.create', 'freq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'register', 'freq' => 'monthly', 'priority' => '0.7'],
        ];

        foreach ($static as $page) {
            $xml .= '<url><loc>'.route($page['route']).'</loc><changefreq>'.$page['freq'].'</changefreq><priority>'.$page['priority'].'</priority></url>';
        }

        foreach (News::published()->get() as $item) {
            $xml .= '<url><loc>'.route('news.show', $item->slug).'</loc><lastmod>'.$item->updated_at->toAtomString().'</lastmod><priority>0.6</priority></url>';
        }
        foreach (PublicCall::published()->get() as $item) {
            $xml .= '<url><loc>'.route('public-calls.show', $item->slug).'</loc><lastmod>'.$item->updated_at->toAtomString().'</lastmod><priority>0.6</priority></url>';
        }
        foreach (Ngo::published()->get() as $item) {
            $xml .= '<url><loc>'.route('ngos.show', $item->slug).'</loc><lastmod>'.$item->updated_at->toAtomString().'</lastmod><priority>0.5</priority></url>';
        }
        foreach (Community::all() as $item) {
            $xml .= '<url><loc>'.route('communities.show', $item->slug).'</loc><lastmod>'.$item->updated_at->toAtomString().'</lastmod><priority>0.5</priority></url>';
        }
        foreach (Event::all() as $item) {
            $xml .= '<url><loc>'.route('events.show', $item->slug).'</loc><lastmod>'.$item->updated_at->toAtomString().'</lastmod><priority>0.5</priority></url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
