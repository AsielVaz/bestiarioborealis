<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('allows a visitor to register and assigns the viewer role', function () {
    Role::findOrCreate('viewer');

    $response = $this->post(route('register'), [
        'name' => 'Nuevo Erudito',
        'email' => 'erudito@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'erudito@example.com')->first();

    $response->assertRedirect(route('dashboard', absolute: false));
    expect($user)->not->toBeNull()
        ->and($user->hasRole('viewer'))->toBeTrue();
    $this->assertAuthenticatedAs($user);
});
