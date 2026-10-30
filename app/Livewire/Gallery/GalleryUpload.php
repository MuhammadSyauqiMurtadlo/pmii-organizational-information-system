<?php

namespace App\Livewire\Gallery;

use App\Models\Activity;
use App\Models\Gallery as GalleryItem;
use App\Models\Rayon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

class GalleryUpload extends Component
{
    use WithFileUploads;
    use WithPagination;

    public array $photos = [];

    public string $title = '';

    public ?int $activity_id = null;

    public bool $is_featured = false;

    public bool $is_public = true;

    public string $filterRayon = '';

    public string $filterActivity = '';

    protected function rules(): array
    {
        return [
            'photos' => ['required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['required', 'image', 'max:5120'],
            'title' => ['required', 'string', 'max:255'],
            'activity_id' => ['nullable', 'integer'],
            'is_featured' => ['boolean'],
            'is_public' => ['boolean'],
        ];
    }

    public function upload(): void
    {
        $user = auth()->user();
        abort_unless($this->canManage($user), 403);

        $data = $this->validate();
        $activity = $data['activity_id']
            ? $this->availableActivities($user)->findOrFail($data['activity_id'])
            : null;
        $storedPaths = [];

        try {
            DB::transaction(function () use ($user, $data, $activity, &$storedPaths): void {
                foreach ($this->photos as $photo) {
                    $path = $photo->store('galleries', 'public');
                    $storedPaths[] = $path;

                    GalleryItem::create([
                        'activity_id' => $activity?->id,
                        'uploader_id' => $user->id,
                        'rayon_id' => $user->isAdminRayon() ? $user->rayon_id : $activity?->rayon_id,
                        'title' => $data['title'],
                        'file_path' => $path,
                        'file_type' => 'photo',
                        'is_featured' => $data['is_featured'],
                        'is_public' => $data['is_public'],
                    ]);
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPaths);

            throw $exception;
        }

        $this->reset('photos', 'title', 'activity_id', 'is_featured');
        $this->is_public = true;
        session()->flash('success', 'Foto berhasil diunggah.');
    }

    public function toggleFeatured(int $id): void
    {
        $gallery = GalleryItem::findOrFail($id);
        Gate::authorize('update', $gallery);
        $gallery->update(['is_featured' => ! $gallery->is_featured]);
    }

    public function togglePublic(int $id): void
    {
        $gallery = GalleryItem::findOrFail($id);
        Gate::authorize('update', $gallery);
        $gallery->update(['is_public' => ! $gallery->is_public]);
    }

    public function deletePhoto(int $id): void
    {
        $gallery = GalleryItem::findOrFail($id);
        Gate::authorize('delete', $gallery);

        DB::transaction(fn () => $gallery->delete());

        if (str_starts_with($gallery->file_path, 'galleries/')) {
            Storage::disk('public')->delete($gallery->file_path);
        }
    }

    public function updatingFilterRayon(): void
    {
        $this->resetPage();
    }

    public function updatingFilterActivity(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $canManageGallery = $this->canManage($user);

        $query = GalleryItem::with(['activity', 'rayon', 'uploader']);
        if (! $canManageGallery) {
            $query->where('is_public', true);
        } elseif ($user->isAdminRayon()) {
            $query->where('rayon_id', $user->rayon_id);
        }

        if ($this->filterRayon && ($user->isSuperAdmin() || $user->isAdminKomisariat())) {
            $query->where('rayon_id', $this->filterRayon);
        }
        if ($this->filterActivity) {
            $query->where('activity_id', $this->filterActivity);
        }

        $galleries = $query->latest()->paginate(20);
        $rayons = $user->isSuperAdmin() || $user->isAdminKomisariat()
            ? Rayon::active()->orderBy('name')->get()
            : collect();
        $activityQuery = $this->availableActivities($user);
        if (! $canManageGallery) {
            $activityQuery->public();
        }
        $activities = $activityQuery->orderByDesc('start_date')->get();

        return view('livewire.gallery.gallery-upload', compact(
            'activities',
            'canManageGallery',
            'galleries',
            'rayons',
        ));
    }

    private function canManage($user): bool
    {
        return $user->can('manage_rayon_gallery') || $user->can('manage_komisariat_gallery');
    }

    private function availableActivities($user)
    {
        return Activity::query()
            ->when(
                $user->isAdminRayon(),
                fn ($query) => $query->where(fn ($scope) => $scope
                    ->whereNull('rayon_id')
                    ->orWhere('rayon_id', $user->rayon_id)),
            );
    }
}
