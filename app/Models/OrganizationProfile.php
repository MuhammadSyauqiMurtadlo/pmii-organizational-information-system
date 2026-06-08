<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'komisariat_id', 'history', 'vision', 'mission',
        'organizational_structure', 'chief_name',
        'secretary_name', 'treasurer_name',
        'period_start', 'period_end',
    ];

    protected $casts = [
        'organizational_structure' => 'array',
    ];

    public function komisariat()
    {
        return $this->belongsTo(Komisariat::class);
    }
}
