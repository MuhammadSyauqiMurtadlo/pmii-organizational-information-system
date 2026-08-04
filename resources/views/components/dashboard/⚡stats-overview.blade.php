<?php

namespace App\Livewire\Dashboard;

use App\Services\DashboardStatisticsService;
use Livewire\Component;

class StatsOverview extends Component
{
    public array $stats = [];

    public function mount(DashboardStatisticsService $service): void
    {
        $user = auth()->user();

        $this->stats = $user->isAdminRayon() && $user->rayon_id ? $service->getRayonStats($user->rayon_id) : $service->getGlobalStats();
    }

    public function render()
    {
        return view('livewire.dashboard.stats-overview');
    }
}
?>
<div>
    {{-- Be present above all else. - Naval Ravikant --}}
</div>
