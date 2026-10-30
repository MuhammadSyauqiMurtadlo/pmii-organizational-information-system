<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;

class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAny(['manage_rayon_activities', 'manage_komisariat_activities']);
    }

    public function view(User $user, Activity $activity): bool
    {
        if (! $this->viewAny($user)) {
            return false;
        }

        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return true;
        }

        return $user->isAdminRayon() && $user->rayon_id === $activity->rayon_id;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

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
