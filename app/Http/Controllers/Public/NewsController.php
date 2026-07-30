<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::published()
            ->with('author', 'rayon')
            ->when($request->kategori, fn ($q, $v) => $q->where('category', $v))
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('public.news.index', compact('news'));
    }

    public function show(string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();
        $news->incrementViews();

        $related = News::published()
            ->where('id', '!=', $news->id)
            ->where('category', $news->category)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('public.news.show', compact('news', 'related'));
    }
}
