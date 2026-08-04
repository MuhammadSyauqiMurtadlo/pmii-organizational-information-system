<?php

namespace App\Livewire\News;

use App\Models\News;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $category = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'category' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteNews(int $id): void
    {
        $news = News::findOrFail($id);

        // Fix: Livewire tidak support $this->authorize(), pakai Gate::authorize()
        Gate::authorize('delete', $news);

        $news->delete();
        session()->flash('success', 'Berita berhasil dihapus.');
    }

    public function toggleStatus(int $id): void
    {
        $news = News::findOrFail($id);

        Gate::authorize('update', $news);

        $news->status === 'draft'
            ? $news->update(['status' => 'published', 'published_at' => now()])
            : $news->update(['status' => 'draft', 'published_at' => null]);
    }

    public function render()
    {
        $user = auth()->user();

        $newsList = News::with(['author', 'rayon'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            // Non-super-admin & non-komisariat hanya lihat berita mereka sendiri
            ->when(
                ! $user->isSuperAdmin() && ! $user->isAdminKomisariat(),
                fn ($q) => $q->where('author_id', $user->id)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.news.news-index', compact('newsList'));
    }
}
?>

<div>
    {{-- Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Maria Skłodowska-Curie --}}
</div>
