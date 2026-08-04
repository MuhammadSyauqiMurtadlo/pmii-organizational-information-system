<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;

class GalleryPolicy
{
    public function delete(User $user, Gallery $gallery): bool
    {
        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return true;
        }

        return $user->id === $gallery->uploader_id;
    }

    public function update(User $user, Gallery $gallery): bool
    {
        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return true;
        }

        return $user->id === $gallery->uploader_id;
    }
}
