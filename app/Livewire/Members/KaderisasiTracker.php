<?php

namespace App\Livewire\Members;

use App\Models\KaderisasiRecord;
use App\Models\Member;
use App\Services\DashboardStatisticsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class KaderisasiTracker extends Component
{
    use WithFileUploads;

    public Member $member;

    public bool $showForm = false;

    public string $type = 'MAPABA';

    public string $status = 'pending';

    public string $event_name = '';

    public string $event_date = '';

    public string $location = '';

    public string $facilitator = '';

    public string $certificate_number = '';

    public $certificate_file = null;

    public function mount(Member $member): void
    {
        $this->member = $member;
        Gate::authorize('view', $member);
    }

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:MAPABA,PKD,PKL,MKDK,other'],
            'status' => ['required', 'in:pending,lulus,tidak_lulus'],
            'event_name' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'facilitator' => ['nullable', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:255'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function saveRecord(): void
    {
        $member = Member::findOrFail($this->member->getKey());
        Gate::authorize('update', $member);
        $data = $this->validate();
        $storedFile = $this->certificate_file?->store('certificates', 'public');

        try {
            DB::transaction(function () use ($member, $data, $storedFile): void {
                $values = [
                    'type' => $data['type'],
                    'status' => $data['status'],
                    'event_name' => $data['event_name'] ?: null,
                    'event_date' => $data['event_date'],
                    'location' => $data['location'] ?: null,
                    'facilitator' => $data['facilitator'] ?: null,
                    'certificate_number' => $data['certificate_number'] ?: null,
                ];

                if ($storedFile) {
                    $values['certificate_file'] = $storedFile;
                }

                $member->kaderisasiRecords()->create($values);
            });
        } catch (Throwable $exception) {
            if ($storedFile) {
                Storage::disk('public')->delete($storedFile);
            }

            throw $exception;
        }

        $this->reset([
            'event_name',
            'event_date',
            'location',
            'facilitator',
            'certificate_number',
            'certificate_file',
        ]);
        $this->type = 'MAPABA';
        $this->status = 'pending';
        $this->showForm = false;
        $this->member->refresh();
        app(DashboardStatisticsService::class)->invalidateCache($member->rayon_id);
    }

    public function deleteRecord(int $id): void
    {
        $member = Member::findOrFail($this->member->getKey());
        Gate::authorize('update', $member);

        $record = $member->kaderisasiRecords()->findOrFail($id);
        DB::transaction(fn () => $record->delete());

        if ($record->certificate_file && str_starts_with($record->certificate_file, 'certificates/')) {
            Storage::disk('public')->delete($record->certificate_file);
        }

        $this->member->refresh();
        app(DashboardStatisticsService::class)->invalidateCache($member->rayon_id);
    }

    public function render()
    {
        $member = Member::with('kaderisasiRecords')->findOrFail($this->member->getKey());
        Gate::authorize('view', $member);
        $records = $member->kaderisasiRecords()->orderByDesc('event_date')->orderByDesc('id')->get();
        $progress = $member->kaderisasi_progress;

        return view('livewire.members.kaderisasi-tracker', compact('member', 'progress', 'records'));
    }
}
