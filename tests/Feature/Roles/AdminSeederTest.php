<?php

use App\Enums\Role;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Hash;

test('crea el primer admin con los datos del .env', function () {
    config(['turnero.admin' => [
        'name' => 'Dueña',
        'email' => 'duena@example.com',
        'password' => 'secreta123',
    ]]);

    $this->seed(AdminSeeder::class);

    $admin = User::where('email', 'duena@example.com')->first();
    expect($admin->role)->toBe(Role::Admin)
        ->and($admin->email_verified_at)->not->toBeNull()
        ->and(Hash::check('secreta123', $admin->password))->toBeTrue();
});

test('correrlo dos veces no duplica ni cambia la contraseña', function () {
    config(['turnero.admin' => ['name' => 'A', 'email' => 'a@example.com', 'password' => 'primera1']]);
    $this->seed(AdminSeeder::class);

    config(['turnero.admin' => ['name' => 'A', 'email' => 'a@example.com', 'password' => 'otra-distinta']]);
    $this->seed(AdminSeeder::class);

    expect(User::where('email', 'a@example.com')->count())->toBe(1)
        ->and(Hash::check('primera1', User::first()->password))->toBeTrue();
});

test('si el email ya existe como cliente, lo convierte en admin', function () {
    User::factory()->create(['email' => 'b@example.com']);
    config(['turnero.admin' => ['name' => 'B', 'email' => 'b@example.com', 'password' => 'x']]);

    $this->seed(AdminSeeder::class);

    expect(User::where('email', 'b@example.com')->first()->role)->toBe(Role::Admin);
});

test('sin ADMIN_EMAIL falla con un mensaje claro', function () {
    config(['turnero.admin' => ['name' => 'X', 'email' => null, 'password' => 'x']]);

    $this->seed(AdminSeeder::class);
})->throws(RuntimeException::class, 'ADMIN_EMAIL');

test('en local genera una contraseña si ADMIN_PASSWORD está vacío', function () {
    config(['turnero.admin' => ['name' => 'C', 'email' => 'c@example.com', 'password' => null]]);

    $this->seed(AdminSeeder::class);

    expect(User::where('email', 'c@example.com')->first()->isAdmin())->toBeTrue();
});
