<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'rayon_id', 'name', 'email', 'password', 'nim',
        'phone', 'avatar', 'status', 'student_faculty',
        'student_major', 'entry_year',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'organizer_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'uploader_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/'.$this->avatar)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=1e8f5e&color=fff';
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isAdminKomisariat(): bool
    {
        return $this->hasRole('admin_komisariat');
    }

    public function isAdminRayon(): bool
    {
        return $this->hasRole('admin_rayon');
    }

    public function isAnggota(): bool
    {
        return $this->hasRole('anggota');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
