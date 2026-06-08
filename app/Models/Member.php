<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'rayon_id', 'member_number', 'joined_date',
        'generation', 'level', 'position', 'bio',
        'address', 'birth_date', 'gender',
    ];

    protected $casts = [
        'joined_date' => 'date',
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }

    public function kaderisasiRecords()
    {
        return $this->hasMany(KaderisasiRecord::class);
    }

    public function activityParticipants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_participants', 'member_id', 'activity_id')
            ->withPivot('attendance', 'notes')
            ->withTimestamps();
    }

    public function hasCompletedKaderisasi(string $type): bool
    {
        return $this->kaderisasiRecords()
            ->where('type', $type)
            ->where('status', 'lulus')
            ->exists();
    }

    public function getKaderisasiProgressAttribute(): array
    {
        $types = ['MAPABA', 'PKD', 'PKL', 'MKDK'];

        return collect($types)->mapWithKeys(fn ($t) => [
            $t => $this->hasCompletedKaderisasi($t),
        ])->toArray();
    }

    public function getLevelBadgeColorAttribute(): string
    {
        return match ($this->level) {
            'kader' => 'gray',
            'anggota_muda' => 'blue',
            'anggota' => 'green',
            'anggota_senior' => 'purple',
            default => 'gray',
        };
    }

    public function scopeByRayon($query, int $rayonId)
    {
        return $query->where('rayon_id', $rayonId);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }
}
