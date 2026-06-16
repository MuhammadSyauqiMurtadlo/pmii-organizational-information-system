<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage_news');
    }

    public function create(User $user): bool
    {
        // manage_rayon_activities sengaja tidak diikutkan —
        // permission manage_news lebih tepat untuk membuat berita
        return $user->hasAnyPermission(['manage_news', 'manage_rayon_activities']);
    }

    public function update(User $user, News $news): bool
    {
        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return true;
        }

        return $user->id === $news->author_id;
    }

    public function delete(User $user, News $news): bool
    {
        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return true;
        }

        return $user->id === $news->author_id;
    }
}
