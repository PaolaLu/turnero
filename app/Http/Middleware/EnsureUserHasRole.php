<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Deja pasar solo a usuarios con alguno de los roles indicados.
 *
 * Uso en rutas:  ->middleware('role:admin')  o  ->middleware('role:admin,profesional')
 * Debe ir después de 'auth'. Si el usuario no tiene el rol, responde 403.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Convertimos los textos de la ruta a enums; un rol mal escrito en la ruta
        // falla enseguida (ValueError) en lugar de bloquear en silencio.
        $permitidos = array_map(fn (string $rol) => Role::from($rol), $roles);

        abort_unless($user && $user->hasRole(...$permitidos), 403);

        return $next($request);
    }
}
