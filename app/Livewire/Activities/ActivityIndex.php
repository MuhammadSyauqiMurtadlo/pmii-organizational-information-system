<?php

namespace App\Livewire\Activities;

use App\Models\Activity;
use App\Services\DashboardStatisticsService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $typeFilter = '';

    public string $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(string $value): void
    {
        if ($value !== '' && ! in_array($value, ['kajian', 'pelatihan', 'rapat', 'aksi', 'sosial', 'kaderisasi', 'lainnya'], true)) {
            $this->typeFilter = '';
        }
    }

    public function updatedStatusFilter(string $value): void
    {
        if ($value !== '' && ! in_array($value, ['upcoming', 'ongoing', 'completed', 'cancelled'], true)) {
            $this->statusFilter = '';
        }
    }

    public function deleteActivity(int $id): void
    {
        $activity = Activity::findOrFail($id);
        Gate::authorize('delete', $activity);
        $poster = $activity->poster;

        $activity->delete();

        if ($poster) {
            Storage::disk('public')->delete($poster);
        }

        app(DashboardStatisticsService::class)->invalidateCache($activity->rayon_id);
        session()->flash('success', 'Kegiatan berhasil dihapus.');
    }

    public function render()
    {
        $user = auth()->user();
        Gate::authorize('viewAny', Activity::class);

        $activities = Activity::query()
            ->with('rayon')
            ->withCount('participants')
            ->when(
                $user->isAdminRayon(),
                fn ($query) => $query->where('rayon_id', $user->rayon_id),
            )
            ->when($this->search !== '', fn ($query) => $query->where(function ($query): void {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('location', 'like', "%{$this->search}%");
            }))
            ->when($this->typeFilter !== '', fn ($query) => $query->where('type', $this->typeFilter))
            ->when($this->statusFilter !== '', fn ($query) => $query->where('status', $this->statusFilter))
            ->orderByDesc('start_date')
            ->paginate(10);

        return view('livewire.activities.activity-index', compact('activities'));
    }
}
