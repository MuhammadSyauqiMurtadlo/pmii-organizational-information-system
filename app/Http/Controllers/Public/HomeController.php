<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Komisariat;
use App\Models\News;
use App\Models\Rayon;
use App\Services\DashboardStatisticsService;

class HomeController extends Controller
{
    public function __construct(private DashboardStatisticsService $statsService) {}

    public function index()
    {
        return view('public.home', [
            // Fix: pakai latestNews() bukan latest() — scope yang sudah kita rename
            'latestNews' => News::latestNews(6)->get(),
            'upcomingActivities' => Activity::upcoming()->with('rayon')->orderBy('start_date')->limit(4)->get(),
            'rayons' => Rayon::active()->withCount('members')->get(),
            'stats' => $this->statsService->getGlobalStats(),
        ]);
    }

    public function about()
    {
        $komisariat = Komisariat::with('organizationProfile')->first();

        return view('public.about', [
            'komisariat' => $komisariat,
            'profile' => $komisariat?->organizationProfile,
            'rayons' => Rayon::active()->withCount('members')->get(),
        ]);
    }

    public function contact()
    {
        return view('public.contact', [
            'komisariat' => Komisariat::first(),
        ]);
    }
}
