<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\KaderisasiRecord;
use App\Models\Member;
use App\Models\News;
use App\Models\Rayon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsService
{
    /**
     * Stats untuk Super Admin & Admin Komisariat (scope global)
     */
    public function getGlobalStats(): array
    {
        return Cache::remember('global_stats', now()->addMinutes(10), function () {
            return [
                'total_members' => Member::count(),
                'total_activities' => Activity::count(),
                'total_rayons' => Rayon::active()->count(),
                'upcoming_activities' => Activity::upcoming()->count(),
                'news_published' => News::published()->count(),
                'members_by_rayon' => Rayon::withCount('members')
                    ->active()
                    ->get()
                    ->map(fn ($r) => [
                        'name' => $r->name,
                        'count' => $r->members_count,
                    ]),
                'kaderisasi_stats' => $this->getKaderisasiStats(),
                'monthly_members' => $this->getMonthlyMembersGrowth(),
            ];
        });
    }

    /**
     * Stats scoped ke rayon tertentu (untuk Admin Rayon)
     */
    public function getRayonStats(int $rayonId): array
    {
        return Cache::remember("rayon_stats_{$rayonId}", now()->addMinutes(10), function () use ($rayonId) {
            return [
                'total_members' => Member::byRayon($rayonId)->count(),
                'active_members' => Member::byRayon($rayonId)
                    ->whereHas('user', fn ($q) => $q->active())
                    ->count(),
                'total_activities' => Activity::where('rayon_id', $rayonId)->count(),
                'upcoming_activities' => Activity::where('rayon_id', $rayonId)->upcoming()->count(),
                'kaderisasi_stats' => $this->getKaderisasiStats($rayonId),
                'members_by_level' => Member::byRayon($rayonId)
                    ->select('level', DB::raw('count(*) as count'))
                    ->groupBy('level')
                    ->pluck('count', 'level'),
            ];
        });
    }

    /**
     * Invalidate cache setelah ada perubahan data
     * Panggil ini dari observer atau setelah operasi CRUD
     */
    public function invalidateCache(?int $rayonId = null): void
    {
        Cache::forget('global_stats');
        if ($rayonId) {
            Cache::forget("rayon_stats_{$rayonId}");
        }
    }

    // ── Private helpers ───────────────────────────────────────

    private function getKaderisasiStats(?int $rayonId = null): array
    {
        $types = ['MAPABA', 'PKD', 'PKL'];

        return collect($types)->mapWithKeys(function ($type) use ($rayonId) {
            $query = KaderisasiRecord::where('type', $type)->where('status', 'lulus');

            if ($rayonId) {
                $query->whereHas('member', fn ($q) => $q->byRayon($rayonId));
            }

            return [$type => $query->count()];
        })->toArray();
    }

    private function getMonthlyMembersGrowth(): array
    {
        return Member::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as count")
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }
}
