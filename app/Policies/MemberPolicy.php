<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['manage_rayon_members', 'view_all_members']);
    }

    public function view(User $user, Member $member): bool
    {
        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return true;
        }
        if ($user->isAdminRayon()) {
            return $user->rayon_id === $member->rayon_id;
        }

        return $user->id === $member->user_id; // anggota bisa lihat profil sendiri
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(['create_komisariat_members', 'create_rayon_members']);
    }

    public function update(User $user, Member $member): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin komisariat bisa update semua member di bawahnya
        if ($user->isAdminKomisariat()) {
            return true;
        }

        if ($user->isAdminRayon()) {
            return $user->rayon_id === $member->rayon_id;
        }

        return $user->id === $member->user_id; // anggota update profil sendiri
    }

    public function delete(User $user, Member $member): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        if ($user->isAdminRayon()) {
            return $user->rayon_id === $member->rayon_id;
        }

        return false;
    }
}
