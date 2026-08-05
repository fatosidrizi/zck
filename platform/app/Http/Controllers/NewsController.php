<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $articles = News::published()
            ->when($request->category, fn($q, $cat) => $q->where('category', $cat))
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('news.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = News::published()->where('slug', $slug)->firstOrFail();
        return view('news.show', compact('article'));
    }
}
