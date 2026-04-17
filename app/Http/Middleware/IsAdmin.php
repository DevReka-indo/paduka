<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Akses ditolak.');
        }

        $level = strtolower($user->level ?? '');

        // Route users & unit-kerja → hanya superadmin
        if ($request->routeIs('users.*') || $request->routeIs('unit-kerja.*')) {
            if ($level !== 'superadmin') {
                abort(403, 'Halaman ini hanya dapat diakses oleh Super Admin.');
            }
            return $next($request);
        }

        // Route temuan & projects → admin dan superadmin
        if ($request->routeIs('temuan.*') || $request->routeIs('projects.*')) {
            if (!in_array($level, ['admin', 'superadmin'])) {
                abort(403, 'Halaman ini hanya dapat diakses oleh Admin.');
            }
            return $next($request);
        }

        // Fallback: semua route dalam group isAdmin minimal harus admin
        if (!in_array($level, ['admin', 'superadmin'])) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
