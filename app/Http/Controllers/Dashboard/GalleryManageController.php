<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class GalleryManageController extends Controller
{
    public function index()
    {
        // Livewire GalleryUpload handle upload & listing
        return view('dashboard.gallery.index');
    }
}
