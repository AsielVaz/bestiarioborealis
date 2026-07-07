<?php

use App\Models\BestiaryEntry;
use App\Models\DossierTheme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('creates an account through the mobile api and returns a token', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Movil Boreal',
        'email' => 'movil@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'device_name' => 'expo-ios',
    ]);

    $response->assertCreated()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('user.email', 'movil@example.com')
        ->assertJsonPath('user.roles.0', 'viewer')
        ->assertJsonStructure(['access_token', 'user' => ['account_id']]);
});

it('logs in through the mobile api and returns the account id', function () {
    $user = User::factory()->create(['email' => 'login-mobile@example.com']);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'login-mobile@example.com',
        'password' => 'password',
        'device_name' => 'expo-android',
    ]);

    $response->assertOk()
        ->assertJsonPath('user.account_id', $user->id)
        ->assertJsonStructure(['access_token']);
});

it('syncs creatures by account id and creature uid', function () {
    DossierTheme::factory()->create(['key' => 'arcane']);
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $upsert = $this->postJson('/api/sync/creatures/upsert', [
        'account_id' => $user->id,
        'creature_uid' => 'local-creature-001',
        'entry' => [
            'title' => 'Sincronizado Boreal',
            'classification' => 'Criatura',
            'threat_level' => 'Media',
            'description' => 'Creada desde el dispositivo.',
            'theme_key' => 'arcane',
        ],
    ]);

    $upsert->assertCreated()
        ->assertJsonPath('entry.creature_uid', 'local-creature-001');

    $this->postJson('/api/sync/creatures/exists', [
        'account_id' => $user->id,
        'creature_uid' => 'local-creature-001',
    ])->assertOk()->assertJsonPath('exists', true);

    $this->postJson('/api/sync/creatures/diff', [
        'account_id' => $user->id,
        'local_creature_uids' => [],
    ])->assertOk()
        ->assertJsonCount(1, 'missing_on_device')
        ->assertJsonPath('missing_on_device.0.creature_uid', 'local-creature-001');
});

it('does not allow a token to sync another account', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    BestiaryEntry::factory()->create(['user_id' => $owner->id, 'sync_uid' => 'private-creature']);
    Sanctum::actingAs($other);

    $this->postJson('/api/sync/creatures/exists', [
        'account_id' => $owner->id,
        'creature_uid' => 'private-creature',
    ])->assertForbidden();
});
