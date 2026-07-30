<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('dashboard.announcements.index');
    }

    public function create()
    {
        return view('dashboard.announcements.create');
    }
}
