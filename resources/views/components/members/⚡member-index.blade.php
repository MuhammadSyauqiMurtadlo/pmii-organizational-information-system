<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\Rayon;
use Livewire\Component;
use Livewire\WithPagination;

class MemberIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $rayonFilter = '';

    public string $levelFilter = '';

    public string $statusFilter = '';

    // Fix: statusFilter ikut disertakan di queryString
    protected $queryString = [
        'search' => ['except' => ''],
        'rayonFilter' => ['except' => ''],
        'levelFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRayonFilter(): void
    {
        $this->resetPage();
    }

    public function updatingLevelFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $query = Member::query()
            ->with(['user', 'rayon'])
            ->when(
                $this->search,
                fn ($q) => $q->whereHas(
                    'user',
                    fn ($u) => $u
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('nim', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%"),
                ),
            )
            ->when($this->levelFilter, fn ($q) => $q->where('level', $this->levelFilter))
            ->when($this->statusFilter, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('status', $this->statusFilter)));

        // RBAC: admin rayon hanya lihat rayonnya sendiri, filter diabaikan
        if ($user->isAdminRayon()) {
            $query->where('rayon_id', $user->rayon_id);
        } elseif ($this->rayonFilter) {
            $query->where('rayon_id', $this->rayonFilter);
        }

        $members = $query->latest()->paginate(15);

        $rayons = $user->isSuperAdmin() || $user->isAdminKomisariat() ? Rayon::active()->get() : collect();

        return view('livewire.members.member-index', compact('members', 'rayons'));
    }
}
?>

<div>
    {{-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi --}}
</div>
