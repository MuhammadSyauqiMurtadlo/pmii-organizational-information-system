<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Announcement;
use App\Services\DashboardStatisticsService;

class DashboardController extends Controller
{
    public function __construct(private DashboardStatisticsService $statsService) {}

    public function index()
    {
        $user = auth()->user();

        $stats = ($user->isAdminRayon() && $user->rayon_id)
            ? $this->statsService->getRayonStats($user->rayon_id)
            : $this->statsService->getGlobalStats();

        $recentActivities = Activity::with('rayon')
            ->when($user->isAdminRayon(), fn ($q) => $q->where('rayon_id', $user->rayon_id))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $announcements = Announcement::forUser($user)
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentActivities', 'announcements'));
    }
}
