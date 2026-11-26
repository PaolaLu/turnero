<?php

use App\Enums\Role;
use App\Models\User;

test('quien se registra queda como cliente aunque mande otro rol', function () {
    $this->post('/register', [
        'name' => 'Intruso',
        'email' => 'intruso@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'admin', // intento de escalar privilegios
    ]);

    expect(User::where('email', 'intruso@example.com')->first()->role)->toBe(Role::Cliente);
});

test('el rol no se puede asignar en masa', function () {
    $usuario = User::create([
        'name' => 'Test',
        'email' => 'masa@example.com',
        'password' => 'password',
        'role' => 'admin',
    ]);

    expect($usuario->fresh()->role)->toBe(Role::Cliente);
});
