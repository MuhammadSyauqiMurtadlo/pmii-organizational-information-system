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
            ->when($this->search, fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->category, fn ($query) => $query->where('category', $this->category))
            ->when(
                ! $user->isSuperAdmin() && ! $user->isAdminKomisariat(),
                fn ($query) => $query->where('author_id', $user->id),
            )
            ->latest()
            ->paginate(10);

        return view('livewire.news.news-index', compact('newsList'));
    }
}
