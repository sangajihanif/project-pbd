<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Pemakaian di route: ->middleware('role:1,3')
     * (1 = Super Admin, 3 = Kasir)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (Auth::check() && in_array((string) Auth::user()->idrole, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Maaf, Anda tidak memiliki hak akses ke halaman ini.');
    }
}
