<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Crea el primer administrador del negocio a partir del .env
 * (ADMIN_NAME, ADMIN_EMAIL, ADMIN_PASSWORD).
 *
 * Se puede correr varias veces: si el email ya existe, solo se asegura de que sea admin
 * y no le cambia la contraseña.
 *
 *   php artisan db:seed --class=AdminSeeder
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $datos = config('turnero.admin');

        if (empty($datos['email'])) {
            throw new RuntimeException('Falta ADMIN_EMAIL en el .env para crear el administrador.');
        }

        $password = $datos['password'];
        $generada = false;

        if (empty($password)) {
            // En producción exigimos una contraseña explícita.
            if (app()->isProduction()) {
                throw new RuntimeException('Falta ADMIN_PASSWORD en el .env (obligatoria en producción).');
            }
            $password = Str::password(16);
            $generada = true;
        }

        $admin = User::firstOrNew(['email' => $datos['email']]);

        if (! $admin->exists) {
            $admin->name = $datos['name'];
            $admin->password = $password; // el cast "hashed" la encripta
            $admin->email_verified_at = now();
        }

        $admin->role = Role::Admin;
        $admin->save();

        if ($admin->wasRecentlyCreated && $generada) {
            $this->command?->warn("Administrador creado: {$admin->email} / contraseña: {$password}");
            $this->command?->warn('Guardala y cambiala desde el perfil.');
        }
    }
}
