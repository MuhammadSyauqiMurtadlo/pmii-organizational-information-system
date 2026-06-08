<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Rayon extends Model
{
    use HasFactory;

    protected $fillable = [
        'komisariat_id', 'name', 'slug', 'faculty',
        'description', 'address', 'logo', 'social_media',
        'founded_year', 'is_active',
    ];

    protected $casts = [
        'social_media' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(fn ($m) => $m->slug ??= Str::slug($m->name));
    }

    public function komisariat()
    {
        return $this->belongsTo(Komisariat::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'target_rayon_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getActiveActivitiesCountAttribute(): int
    {
        return $this->activities()->whereIn('status', ['upcoming', 'ongoing'])->count();
    }
}
