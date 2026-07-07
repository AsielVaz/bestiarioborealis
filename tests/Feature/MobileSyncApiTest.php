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

it('stores the full mobile creature payload', function () {
    DossierTheme::factory()->create(['key' => 'arcane']);
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $payload = [
        'account_id' => $user->id,
        'creature_uid' => '8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14',
        'entry' => [
            'title' => 'Borealis Examplitus',
            'classification' => 'Archivista arcano / Demiurgo taxonómico',
            'category' => 'Creador del bestiario',
            'threat_level' => 'Autoridad fundacional',
            'height' => '1.86 m / variable bajo eclipse',
            'description' => 'No creó monstruos: creó una forma de recordarlos sin que devoraran la memoria de quienes los vieron.',
            'last_record' => 'Fue visto cerrando el Volumen Cero al amanecer, antes de dejar instrucciones para que otros completaran el archivo.',
            'status' => 'Activo / Fundador / Guardián editorial',
            'theme_key' => 'arcane',
            'origin' => [
                'universe' => 'Archivo Boreal',
                'game' => 'BorsBestiario',
                'campaign' => 'Volumen Fundacional',
                'source' => 'Primer folio autógrafo',
                'region' => 'Observatorio de las Mareas Altas',
            ],
            'subtitles' => [
                'El Cartógrafo del Umbral',
                'Fundador del Bestiario',
                'La Pluma que Clasifica lo Imposible',
            ],
            'affinities' => ['Aurora', 'Tinta viva', 'Cartografía astral', 'Memoria náutica'],
            'habitats' => ['Biblioteca septentrional', 'Cartas náuticas imposibles', 'Observatorio sellado'],
            'behaviors' => ['Meticuloso', 'Hospitalario', 'Críptico', 'Protector de registros', 'Implacable con falsificaciones'],
            'abilities' => [
                ['name' => 'Pluma de indexación', 'description' => 'Convierte testimonios dispersos en fichas coherentes sin borrar contradicciones útiles.'],
                ['name' => 'Atlas de costas invisibles', 'description' => 'Traza rutas hacia regiones que solo existen cuando una criatura ha sido nombrada.'],
            ],
            'techniques' => [
                ['name' => 'Catalogación de umbral', 'description' => 'Analiza el origen de una entidad y fija sus rasgos antes de que cambie de forma.'],
                ['name' => 'Ancla de tinta boreal', 'description' => 'Crea un círculo de sellos que impide que un registro sea reescrito durante el combate.'],
            ],
            'weaknesses' => [
                ['description' => 'No puede destruir un registro una vez confirmado.'],
                ['description' => 'Su autoridad depende de evidencia, testimonio y contraste.'],
            ],
            'loot' => [
                ['name' => 'Pluma de procedencia', 'description' => 'Instrumento que firma mapas y fichas con tinta imposible de falsificar.', 'rarity' => 'Reliquia'],
                ['name' => 'Fragmento del Volumen Cero', 'description' => 'Hoja resistente al fuego, al agua y a la memoria alterada.', 'rarity' => 'Legendario'],
            ],
            'stats' => [
                ['name' => 'Erudición', 'value' => 97, 'value_label' => 'Fundacional'],
                ['name' => 'Cartografía', 'value' => 93, 'value_label' => 'Magistral'],
                ['name' => 'Combate directo', 'value' => 28, 'value_label' => 'Evitado'],
            ],
            'vignettes' => [
                ['title' => 'Primer folio', 'description' => 'La página donde se definió la forma del dossier arcano.'],
                ['title' => 'Carta náutica boreal', 'description' => 'Mapa usado para ubicar criaturas que migran entre universos.'],
            ],
            'scholar_notes' => [
                ['note' => 'Borealis nunca firma al inicio. Prefiere que la evidencia hable primero.'],
                ['note' => 'Su bestiario admite criaturas, aliados, entidades, errores y milagros con la misma disciplina.'],
            ],
            'final_combat_scenario' => 'El enfrentamiento ocurre en el Observatorio de las Mareas Altas mientras el archivo gira como una carta náutica viva.',
        ],
    ];

    $this->postJson('/api/sync/creatures/upsert', $payload)->assertCreated();

    $entry = \App\Models\BestiaryEntry::where('sync_uid', $payload['creature_uid'])->firstOrFail();
    $entry->load(['origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes']);

    expect($entry->category)->toBe('Creador del bestiario')
        ->and($entry->final_combat_scenario)->toStartWith('El enfrentamiento ocurre')
        ->and($entry->origin->region)->toBe('Observatorio de las Mareas Altas')
        ->and($entry->subtitles)->toHaveCount(3)
        ->and($entry->affinities)->toHaveCount(4)
        ->and($entry->habitats)->toHaveCount(3)
        ->and($entry->behaviors)->toHaveCount(5)
        ->and($entry->abilities)->toHaveCount(2)
        ->and($entry->techniques)->toHaveCount(2)
        ->and($entry->weaknesses)->toHaveCount(2)
        ->and($entry->loot)->toHaveCount(2)
        ->and($entry->stats)->toHaveCount(3)
        ->and($entry->vignettes)->toHaveCount(2)
        ->and($entry->scholarNotes)->toHaveCount(2)
        ->and($entry->source_payload['title'])->toBe('Borealis Examplitus');
});
