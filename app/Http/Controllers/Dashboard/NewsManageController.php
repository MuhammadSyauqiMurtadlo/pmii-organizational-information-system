<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsManageController extends Controller
{
    public function index()
    {
        // Livewire NewsIndex handle filter & pagination
        return view('dashboard.news.index');
    }

    public function create()
    {
        return view('dashboard.news.create');
    }

    public function edit(News $news)
    {
        $this->authorize('update', $news);

        return view('dashboard.news.edit', compact('news'));
    }
}
