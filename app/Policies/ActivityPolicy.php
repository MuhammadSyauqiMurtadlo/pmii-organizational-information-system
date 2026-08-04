<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;

class ActivityPolicy
{
    public function update(User $user, Activity $activity): bool
    {
        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return true;
        }
        if ($user->isAdminRayon()) {
            return $user->rayon_id === $activity->rayon_id;
        }

        return $user->id === $activity->organizer_id;
    }

    public function delete(User $user, Activity $activity): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        if ($user->isAdminRayon()) {
            return $user->rayon_id === $activity->rayon_id;
        }

        return false;
    }
}
