<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Komisariat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'vision', 'mission',
        'address', 'phone', 'email', 'logo', 'banner',
        'social_media', 'founded_year', 'is_active',
    ];

    protected $casts = [
        'social_media' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(fn ($model) => $model->slug ??= Str::slug($model->name));
    }

    // ── Relationships ──────────────────────────────────────────
    public function rayons()
    {
        return $this->hasMany(Rayon::class);
    }

    public function activeRayons()
    {
        return $this->hasMany(Rayon::class)->where('is_active', true);
    }

    public function organizationProfile()
    {
        return $this->hasOne(OrganizationProfile::class);
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Accessors ────────────────────────────────────────────
    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/'.$this->logo)
            : asset('images/default-logo.png');
    }
}
