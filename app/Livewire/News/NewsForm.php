<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Services\DashboardStatisticsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class NewsForm extends Component
{
    use WithFileUploads;

    public ?News $news = null;

    public bool $isEdit = false;

    public string $title = '';

    public string $excerpt = '';

    public string $content = '';

    public string $category = 'berita';

    public string $status = 'draft';

    public string $tags = '';

    public $thumbnail = null;

    public function mount(?News $news = null): void
    {
        if ($news?->exists) {
            Gate::authorize('update', $news);
            $this->news = $news;
            $this->isEdit = true;
            $this->fill([
                'title' => $news->title,
                'excerpt' => $news->excerpt ?? '',
                'content' => $news->content,
                'category' => $news->category,
                'status' => $news->status,
                'tags' => implode(', ', $news->tags ?? []),
            ]);

            return;
        }

        Gate::authorize('create', News::class);
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'content' => ['required', 'string'],
            'category' => ['required', 'in:berita,artikel,opini,press_release'],
            'status' => ['required', 'in:draft,published,archived'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function save(): void
    {
        $news = $this->isEdit ? News::findOrFail($this->news->getKey()) : null;

        if ($this->isEdit) {
            Gate::authorize('update', $news);
        } else {
            Gate::authorize('create', News::class);
        }

        $data = $this->validate();
        $storedThumbnail = $this->thumbnail?->store('news', 'public');
        $oldThumbnail = $news->thumbnail ?? null;
        $tags = collect(explode(',', $data['tags'] ?? ''))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();

        try {
            DB::transaction(function () use ($data, $news, $storedThumbnail, $tags): void {
                $values = [
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'] ?: null,
                    'content' => $data['content'],
                    'category' => $data['category'],
                    'status' => $data['status'],
                    'tags' => $tags ?: null,
                    'published_at' => $data['status'] === 'published'
                        ? ($news?->published_at ?? now())
                        : null,
                ];

                if ($storedThumbnail) {
                    $values['thumbnail'] = $storedThumbnail;
                }

                if ($news) {
                    $news->update($values);
                } else {
                    $values['author_id'] = auth()->id();
                    $values['slug'] = $this->uniqueSlug($data['title']);
                    $news = News::create($values);
                    $this->news = $news;
                }
            });
        } catch (Throwable $exception) {
            if ($storedThumbnail) {
                Storage::disk('public')->delete($storedThumbnail);
            }

            throw $exception;
        }

        if ($storedThumbnail && $oldThumbnail && $oldThumbnail !== $storedThumbnail) {
            $this->deleteStoredThumbnail($oldThumbnail);
        }

        app(DashboardStatisticsService::class)->invalidateCache();
        session()->flash('success', $this->isEdit ? 'Berita berhasil diperbarui.' : 'Berita berhasil dibuat.');
        $this->redirect(route('dashboard.news.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.news.news-form');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'news';
        $slug = $base;
        $suffix = 2;

        while (News::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function deleteStoredThumbnail(string $path): void
    {
        if (str_starts_with($path, 'news/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
