<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaderisasiRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'type', 'event_name', 'event_date',
        'location', 'facilitator', 'status',
        'certificate_number', 'certificate_file', 'notes',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'MAPABA' => 'Masa Penerimaan Anggota Baru',
            'PKD' => 'Pelatihan Kader Dasar',
            'PKL' => 'Pelatihan Kader Lanjut',
            'MKDK' => 'Masa Kesetiaan Anggota',
            default => $this->type,
        };
    }

    public function scopeLulus($query)
    {
        return $query->where('status', 'lulus');
    }
}
