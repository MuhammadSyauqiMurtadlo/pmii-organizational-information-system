<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id', 'rayon_id', 'title', 'slug', 'excerpt',
        'content', 'thumbnail', 'category', 'status',
        'published_at', 'views', 'tags',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'views' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(fn ($m) => $m->slug ??= Str::slug($m->title));
        static::creating(function ($m) {
            if ($m->status === 'published' && ! $m->published_at) {
                $m->published_at = now();
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    // Override bawaan Laravel biar tidak konflik dengan scopePublished
    public function scopeLatestNews($query, int $limit = 5)
    {
        return $query->published()->orderByDesc('published_at')->limit($limit);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/'.$this->thumbnail)
            : asset('images/default-news.jpg');
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
