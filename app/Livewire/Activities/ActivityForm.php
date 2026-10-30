<?php

namespace App\Livewire\Activities;

use App\Models\Activity;
use App\Models\Rayon;
use App\Services\DashboardStatisticsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ActivityForm extends Component
{
    use WithFileUploads;

    public ?Activity $activity = null;

    public bool $isEdit = false;

    public string $title = '';

    public string $start_date = '';

    public string $end_date = '';

    public string $location = '';

    public string $description = '';

    public string $objective = '';

    public string $type = 'lainnya';

    public string $status = 'upcoming';

    public string|int|null $rayon_id = '';

    public string|int|null $max_participants = '';

    public $poster = null;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'objective' => ['nullable', 'string', 'max:10000'],
            'type' => ['required', Rule::in(['kajian', 'pelatihan', 'rapat', 'aksi', 'sosial', 'kaderisasi', 'lainnya'])],
            'status' => ['required', Rule::in(['upcoming', 'ongoing', 'completed', 'cancelled'])],
            'rayon_id' => ['nullable', 'integer', 'exists:rayons,id'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'poster' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function mount(?Activity $activity = null): void
    {
        if ($activity?->exists) {
            Gate::authorize('update', $activity);
            $this->activity = $activity;
            $this->isEdit = true;

            $this->fill([
                'title' => $activity->title,
                'start_date' => $activity->start_date->format('Y-m-d\TH:i'),
                'end_date' => $activity->end_date->format('Y-m-d\TH:i'),
                'location' => $activity->location,
                'description' => $activity->description ?? '',
                'objective' => $activity->objective ?? '',
                'type' => $activity->type,
                'status' => $activity->status,
                'rayon_id' => $activity->rayon_id ?? '',
                'max_participants' => $activity->max_participants ?? '',
            ]);
        } else {
            Gate::authorize('create', Activity::class);

            if (auth()->user()->isAdminRayon()) {
                $this->rayon_id = auth()->user()->rayon_id;
            }
        }
    }

    public function save(): void
    {
        if ($this->isEdit) {
            Gate::authorize('update', $this->activity);
        } else {
            Gate::authorize('create', Activity::class);
        }

        $this->rayon_id = $this->rayon_id === '' ? null : $this->rayon_id;
        $this->max_participants = $this->max_participants === '' ? null : $this->max_participants;
        $validated = $this->validate();

        if (auth()->user()->isAdminRayon()) {
            $validated['rayon_id'] = auth()->user()->rayon_id;
        }

        $oldPoster = $this->isEdit ? $this->activity->poster : null;
        $oldRayonId = $this->isEdit ? $this->activity->rayon_id : null;
        $newPoster = null;

        try {
            DB::transaction(function () use (&$newPoster, $validated): void {
                if ($this->poster) {
                    $newPoster = $this->poster->store('activities', 'public');
                }

                $attributes = [
                    ...$validated,
                    'description' => $validated['description'] ?: null,
                    'objective' => $validated['objective'] ?: null,
                    'rayon_id' => $validated['rayon_id'] ?: null,
                    'max_participants' => $validated['max_participants'] ?: null,
                ];
                unset($attributes['poster']);

                if ($newPoster) {
                    $attributes['poster'] = $newPoster;
                }

                if ($this->isEdit) {
                    $attributes['slug'] = $this->uniqueSlug($validated['title'], $this->activity->id);
                    $this->activity->update($attributes);
                } else {
                    $attributes['organizer_id'] = auth()->id();
                    $attributes['slug'] = $this->uniqueSlug($validated['title']);
                    $this->activity = Activity::create($attributes);
                }
            });
        } catch (\Throwable $exception) {
            if ($newPoster) {
                Storage::disk('public')->delete($newPoster);
            }

            throw $exception;
        }

        if ($newPoster && $oldPoster) {
            Storage::disk('public')->delete($oldPoster);
        }

        $statistics = app(DashboardStatisticsService::class);
        $statistics->invalidateCache($oldRayonId);
        if ($this->activity->rayon_id !== $oldRayonId) {
            $statistics->invalidateCache($this->activity->rayon_id);
        }

        session()->flash('success', $this->isEdit ? 'Kegiatan berhasil diperbarui.' : 'Kegiatan berhasil dibuat.');
        $this->redirect(route('dashboard.activities.index'), navigate: true);
    }

    public function render()
    {
        $user = auth()->user();
        Gate::authorize($this->isEdit ? 'update' : 'create', $this->isEdit ? $this->activity : Activity::class);
        $rayons = $user->isSuperAdmin() || $user->isAdminKomisariat()
            ? Rayon::active()->get()
            : ($user->isAdminRayon() ? Rayon::whereKey($user->rayon_id)->get() : collect());

        return view('livewire.activities.activity-form', compact('rayons'));
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'kegiatan';
        $slug = $base;
        $suffix = 2;

        while (Activity::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
