<?php

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Rutas de prueba, solo para estos tests, protegidas por el middleware "role".
beforeEach(function () {
    Route::middleware(['web', 'auth', 'role:admin'])
        ->get('/_test/solo-admin', fn () => 'ok');

    Route::middleware(['web', 'auth', 'role:admin,profesional'])
        ->get('/_test/equipo', fn () => 'ok');
});

test('el admin entra a una ruta solo para admin', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/_test/solo-admin')
        ->assertOk();
});

test('profesional y cliente reciben 403 en una ruta solo para admin', function (Role $rol) {
    $usuario = User::factory()->create(['role' => $rol]);

    $this->actingAs($usuario)
        ->get('/_test/solo-admin')
        ->assertForbidden();
})->with([Role::Profesional, Role::Cliente]);

test('una ruta acepta varios roles separados por coma', function () {
    $this->actingAs(User::factory()->admin()->create())->get('/_test/equipo')->assertOk();
    $this->actingAs(User::factory()->profesional()->create())->get('/_test/equipo')->assertOk();
    $this->actingAs(User::factory()->create())->get('/_test/equipo')->assertForbidden();
});

test('un visitante sin sesión es enviado al login', function () {
    $this->get('/_test/solo-admin')->assertRedirect('/login');
});

test('los helpers del modelo reconocen cada rol', function () {
    $admin = User::factory()->admin()->create();
    $cliente = User::factory()->create();

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->hasRole(Role::Admin, Role::Profesional))->toBeTrue()
        ->and($cliente->isCliente())->toBeTrue()
        ->and($cliente->role)->toBe(Role::Cliente)
        ->and($cliente->hasRole(Role::Admin))->toBeFalse();
});
