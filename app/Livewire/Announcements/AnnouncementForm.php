<?php

namespace App\Livewire\Announcements;

use App\Models\Announcement;
use App\Models\Rayon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class AnnouncementForm extends Component
{
    use WithFileUploads;

    public ?Announcement $announcement = null;

    public bool $isEdit = false;

    public string $title = '';

    public string $content = '';

    public string $priority = 'normal';

    public string $target_scope = 'all';

    public ?int $target_rayon_id = null;

    public bool $is_pinned = false;

    public ?string $expires_at = null;

    public $attachment = null;

    public function mount(?Announcement $announcement = null): void
    {
        $this->authorizeManagement();

        if ($announcement?->exists) {
            $this->announcement = $announcement;
            $this->isEdit = true;
            $this->fill([
                'title' => $announcement->title,
                'content' => $announcement->content,
                'priority' => $announcement->priority,
                'target_scope' => $announcement->target_scope,
                'target_rayon_id' => $announcement->target_rayon_id,
                'is_pinned' => $announcement->is_pinned,
                'expires_at' => $announcement->expires_at?->format('Y-m-d\TH:i'),
            ]);
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'target_scope' => ['required', 'in:all,komisariat,rayon'],
            'target_rayon_id' => ['nullable', 'required_if:target_scope,rayon', 'exists:rayons,id'],
            'is_pinned' => ['boolean'],
            'expires_at' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png', 'max:10240'],
        ];
    }

    public function updatedTargetScope(): void
    {
        if ($this->target_scope !== 'rayon') {
            $this->target_rayon_id = null;
            $this->resetValidation('target_rayon_id');
        }
    }

    public function save(): void
    {
        $this->authorizeManagement();
        $data = $this->validate();

        $storedAttachment = $this->attachment?->store('announcements', 'public');
        $oldAttachment = $this->announcement?->attachment;

        try {
            DB::transaction(function () use ($data, $storedAttachment): void {
                $values = [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'priority' => $data['priority'],
                    'target_scope' => $data['target_scope'],
                    'target_rayon_id' => $data['target_scope'] === 'rayon' ? $data['target_rayon_id'] : null,
                    'is_pinned' => $data['is_pinned'],
                    'expires_at' => $data['expires_at'] ?: null,
                ];

                if ($storedAttachment) {
                    $values['attachment'] = $storedAttachment;
                }

                if ($this->isEdit) {
                    $this->announcement->update($values);
                } else {
                    $values['author_id'] = auth()->id();
                    $this->announcement = Announcement::create($values);
                }
            });
        } catch (Throwable $exception) {
            if ($storedAttachment) {
                Storage::disk('public')->delete($storedAttachment);
            }

            throw $exception;
        }

        if ($storedAttachment && $oldAttachment && $oldAttachment !== $storedAttachment) {
            $this->deleteStoredFile($oldAttachment);
        }

        session()->flash('success', $this->isEdit ? 'Pengumuman berhasil diperbarui.' : 'Pengumuman berhasil dibuat.');
        $this->redirect(route('dashboard.announcements.index'), navigate: true);
    }

    public function render()
    {
        $rayons = Rayon::active()->orderBy('name')->get();

        return view('livewire.announcements.announcement-form', compact('rayons'));
    }

    private function authorizeManagement(): void
    {
        abort_unless(auth()->user()?->can('manage_announcements'), 403);
    }

    private function deleteStoredFile(string $path): void
    {
        if (str_starts_with($path, 'announcements/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
