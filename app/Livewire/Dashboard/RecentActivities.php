<?php

namespace App\Livewire\Dashboard;

use App\Models\Activity;
use Livewire\Component;

class RecentActivities extends Component
{
    public function render()
    {
        $user = auth()->user();
        $activities = Activity::query()
            ->with('rayon')
            ->when($user->isAdminRayon(), fn ($query) => $query->where('rayon_id', $user->rayon_id))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.recent-activities', compact('activities'));
    }
}
