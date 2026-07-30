<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::public()
            ->with('rayon', 'organizer')
            ->when($request->tipe, fn ($q, $v) => $q->where('type', $v))
            ->orderByDesc('start_date')
            ->paginate(12);

        return view('public.activities.index', compact('activities'));
    }

    public function show(string $slug)
    {
        $activity = Activity::public()
            ->with(['rayon', 'organizer', 'galleries' => fn ($q) => $q->public()])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.activities.show', compact('activity'));
    }
}
