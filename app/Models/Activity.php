<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id', 'rayon_id', 'title', 'slug', 'description',
        'objective', 'location', 'start_date', 'end_date',
        'type', 'status', 'poster', 'max_participants', 'report',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(fn ($m) => $m->slug ??= Str::slug($m->title));
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }

    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'activity_participants')
            ->withPivot('attendance', 'notes')
            ->withTimestamps();
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')->where('start_date', '>', now());
    }

    public function scopePublic($query)
    {
        return $query->whereIn('status', ['upcoming', 'ongoing', 'completed']);
    }

    public function getIsKomisariatLevelAttribute(): bool
    {
        return is_null($this->rayon_id);
    }

    public function getParticipantCountAttribute(): int
    {
        return $this->participants()->count();
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'upcoming' => ['label' => 'Akan Datang',        'color' => 'blue'],
            'ongoing' => ['label' => 'Sedang Berlangsung', 'color' => 'green'],
            'completed' => ['label' => 'Selesai',            'color' => 'gray'],
            'cancelled' => ['label' => 'Dibatalkan',         'color' => 'red'],
            default => ['label' => $this->status,        'color' => 'gray'],
        };
    }
}
