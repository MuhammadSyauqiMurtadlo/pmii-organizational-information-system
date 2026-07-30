<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class ActivityManageController extends Controller
{
    public function index()
    {
        return view('dashboard.activities.index');
    }

    public function create()
    {
        return view('dashboard.activities.create');
    }

    public function show(Activity $activity)
    {
        $activity->load(['rayon', 'organizer', 'participants.member.user', 'galleries']);

        return view('dashboard.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $this->authorize('update', $activity);

        return view('dashboard.activities.edit', compact('activity'));
    }
}
