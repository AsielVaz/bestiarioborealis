<?php

use App\Models\BestiaryEntry;
use App\Models\DossierTheme;
use App\Models\User;
use App\Services\BestiaryEntryService;
use App\Services\CreatureGenerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a bestiary entry from service data', function () {
    $theme = DossierTheme::factory()->create(['key' => 'arcane']);
    $user = User::factory()->create();

    $entry = app(BestiaryEntryService::class)->createFromJson([
        'title' => 'Archivista Boreal',
        'classification' => 'PNJ',
        'threat_level' => 'Baja',
        'description' => 'Custodia indices antiguos.',
        'theme_key' => $theme->key,
        'stats' => [['name' => 'Arcano', 'value' => 64]],
    ], $user->id);

    expect($entry)->toBeInstanceOf(BestiaryEntry::class)
        ->and($entry->stats)->toHaveCount(1)
        ->and($entry->dossier_theme_id)->toBe($theme->id);
});

it('imports json through the protected web endpoint', function () {
    DossierTheme::factory()->create(['key' => 'arcane']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('import.store'), [
            'json' => json_encode([
                'title' => 'Lector Umbral',
                'classification' => 'Entidad',
                'threat_level' => 'Alta',
                'description' => 'Lee nombres verdaderos desde el margen.',
                'theme_key' => 'arcane',
            ]),
        ])
        ->assertRedirect();

    expect(BestiaryEntry::where('title', 'Lector Umbral')->exists())->toBeTrue();
});

it('generates a creature without calling the external ai provider in tests', function () {
    DossierTheme::factory()->create(['key' => 'arcane']);
    $user = User::factory()->create();

    $entry = app(CreatureGenerationService::class)->generateFromDescription('Una criatura de biblioteca con escarcha y tinta viva.', $user->id);

    expect($entry->title)->toBe('Criatura generada')
        ->and($entry->abilities)->toHaveCount(1);
});
