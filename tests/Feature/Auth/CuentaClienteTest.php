<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

// HU3: el cliente crea su cuenta, verifica el email y puede recuperar la contraseña, todo en español.

test('al registrarse se envía el email de verificación', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Ana Cliente',
        'email' => 'ana@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    Notification::assertSentTo(User::where('email', 'ana@example.com')->first(), VerifyEmail::class);
});

test('un cliente sin verificar no entra a las secciones protegidas', function () {
    $cliente = User::factory()->unverified()->create();

    $this->actingAs($cliente)
        ->get('/dashboard')
        ->assertRedirect(route('verification.notice'));
});

test('la pantalla de verificación está en español', function () {
    $cliente = User::factory()->unverified()->create();

    $this->actingAs($cliente)
        ->get(route('verification.notice'))
        ->assertOk()
        ->assertSee('¡Gracias por registrarte!')
        ->assertSee('Reenviar email de verificación');
});

test('el email de verificación está en español', function () {
    $cliente = User::factory()->unverified()->create();

    $mail = (new VerifyEmail)->toMail($cliente);

    expect($mail->subject)->toBe('Verificar email')
        ->and($mail->actionText)->toBe('Verificar email');
});

test('el email para restablecer la contraseña está en español', function () {
    $cliente = User::factory()->create();

    $mail = (new ResetPassword('token-de-prueba'))->toMail($cliente);

    expect($mail->subject)->toBe('Restablecer tu contraseña')
        ->and($mail->introLines[0])->toContain('se pidió restablecer la contraseña');
});

test('los errores de validación del registro están en español', function () {
    $this->post('/register', [])
        ->assertSessionHasErrors([
            'name' => 'El nombre es obligatorio.',
            'email' => 'El email es obligatorio.',
        ]);
});

test('un email ya registrado muestra el error en español', function () {
    User::factory()->create(['email' => 'repetido@example.com']);

    $this->post('/register', [
        'name' => 'Otra',
        'email' => 'repetido@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors(['email' => 'El email ya está en uso.']);
});

test('login con datos incorrectos muestra el error en español', function () {
    $cliente = User::factory()->create();

    $this->post('/login', ['email' => $cliente->email, 'password' => 'incorrecta'])
        ->assertSessionHasErrors(['email' => 'El email o la contraseña no son correctos.']);
});
