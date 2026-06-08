<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id', 'title', 'content', 'priority',
        'target_scope', 'target_rayon_id',
        'is_pinned', 'attachment', 'expires_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function targetRayon()
    {
        return $this->belongsTo(Rayon::class, 'target_rayon_id');
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    public function scopeForUser($query, User $user)
    {
        return $query->active()->where(function ($q) use ($user) {
            $q->where('target_scope', 'all');
            if ($user->isAdminKomisariat()) {
                $q->orWhere('target_scope', 'komisariat');
            }
            if ($user->rayon_id) {
                $q->orWhere(function ($inner) use ($user) {
                    $inner->where('target_scope', 'rayon')
                        ->where('target_rayon_id', $user->rayon_id);
                });
            }
        });
    }

    public function getPriorityBadgeAttribute(): array
    {
        return match ($this->priority) {
            'low' => ['label' => 'Rendah',   'color' => 'gray'],
            'normal' => ['label' => 'Normal',   'color' => 'blue'],
            'high' => ['label' => 'Penting',  'color' => 'orange'],
            'urgent' => ['label' => 'Mendesak', 'color' => 'red'],
            default => ['label' => 'Normal',   'color' => 'blue']
        };
    }
}
