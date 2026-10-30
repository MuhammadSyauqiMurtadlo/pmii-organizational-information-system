<?php

namespace App\Livewire\Activities;

use App\Models\Activity;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ActivityParticipants extends Component
{
    public Activity $activity;

    public bool $showAddForm = false;

    public string $searchMember = '';

    public ?int $selectedMember = null;

    public function mount(Activity $activity): void
    {
        $this->activity = $activity;
        Gate::authorize('view', $activity);
    }

    public function addParticipant(): void
    {
        Gate::authorize('update', $this->activity);
        $this->resetValidation();

        $this->validate([
            'selectedMember' => ['required', 'integer', Rule::exists('members', 'id')],
        ]);

        $member = Member::findOrFail($this->selectedMember);

        if ($this->activity->rayon_id && $member->rayon_id !== $this->activity->rayon_id) {
            $this->addError('selectedMember', 'Anggota harus berasal dari rayon kegiatan ini.');

            return;
        }

        if ($this->activity->participants()->where('member_id', $member->id)->exists()) {
            $this->addError('selectedMember', 'Anggota ini sudah terdaftar sebagai peserta.');

            return;
        }

        DB::transaction(function () use ($member): void {
            $activity = Activity::query()->lockForUpdate()->findOrFail($this->activity->id);
            $participantCount = $activity->participants()->count();

            if ($activity->max_participants && $participantCount >= $activity->max_participants) {
                $this->addError('selectedMember', 'Kapasitas peserta kegiatan sudah penuh.');

                return;
            }

            $activity->participants()->create([
                'member_id' => $member->id,
                'attendance' => 'registered',
            ]);
        });

        if ($this->getErrorBag()->has('selectedMember')) {
            return;
        }

        $this->reset(['selectedMember', 'searchMember', 'showAddForm']);
        session()->flash('success', 'Peserta berhasil ditambahkan.');
    }

    public function updateAttendance(int $participantId, string $attendance): void
    {
        Gate::authorize('update', $this->activity);

        Validator::make(
            ['attendance' => $attendance],
            ['attendance' => ['required', Rule::in(['registered', 'attended', 'absent'])]],
        )->validate();

        $participant = $this->activity->participants()->findOrFail($participantId);
        $participant->update(['attendance' => $attendance]);
    }

    public function removeParticipant(int $participantId): void
    {
        Gate::authorize('update', $this->activity);

        $this->activity->participants()->findOrFail($participantId)->delete();
        session()->flash('success', 'Peserta berhasil dihapus.');
    }

    public function render()
    {
        $participants = $this->activity->participants()
            ->with(['member.user', 'member.rayon'])
            ->orderBy('id')
            ->get();

        $availableMembers = collect();
        if ($this->showAddForm) {
            $availableMembers = Member::query()
                ->with(['user', 'rayon'])
                ->when($this->activity->rayon_id, fn ($query) => $query->where('rayon_id', $this->activity->rayon_id))
                ->whereDoesntHave('activityParticipants', fn ($query) => $query->where('activity_id', $this->activity->id))
                ->when($this->searchMember !== '', fn ($query) => $query->whereHas(
                    'user',
                    fn ($userQuery) => $userQuery->where('name', 'like', "%{$this->searchMember}%"),
                ))
                ->orderBy('id')
                ->limit(20)
                ->get();
        }

        return view('livewire.activities.activity-participants', compact('participants', 'availableMembers'));
    }
}
