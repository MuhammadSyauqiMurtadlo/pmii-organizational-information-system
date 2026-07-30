<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::public()
            ->with('rayon', 'activity')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(24);

        return view('public.gallery', compact('galleries'));
    }
}
