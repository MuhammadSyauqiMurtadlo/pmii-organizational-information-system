<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class RayonController extends Controller
{
    public function index()
    {
        // Hanya super_admin (dijaga di route)
        return view('dashboard.settings.rayons');
    }
}
