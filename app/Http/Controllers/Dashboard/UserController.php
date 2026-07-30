<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        // Hanya super_admin & admin_komisariat (dijaga di route)
        return view('dashboard.settings.users');
    }
}
