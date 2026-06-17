<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRayonAccess
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // Super admin dan admin komisariat bypass semua
        if ($user->isSuperAdmin() || $user->isAdminKomisariat()) {
            return $next($request);
        }

        // Admin rayon hanya bisa akses rayonnya sendiri
        if ($user->isAdminRayon()) {
            // Coba ambil rayon_id dari route parameter (bisa berbagai nama)
            $rayonId = $request->route('rayon')
                ?? $request->route('rayon_id')
                ?? $request->input('rayon_id');

            if ($rayonId && (int) $user->rayon_id !== (int) $rayonId) {
                abort(403, 'Anda tidak memiliki akses ke rayon ini.');
            }
        }

        return $next($request);
    }
}
