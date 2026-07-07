<?php

use App\Models\BestiaryEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('only lists entries created by the authenticated user in the web admin', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    BestiaryEntry::factory()->create(['user_id' => $owner->id, 'title' => 'Ficha propia']);
    BestiaryEntry::factory()->create(['user_id' => $other->id, 'title' => 'Ficha ajena']);

    $this->actingAs($owner)
        ->get(route('entries.index'))
        ->assertOk()
        ->assertSee('Ficha propia')
        ->assertDontSee('Ficha ajena');
});

it('blocks web access to entries owned by another user', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $foreignEntry = BestiaryEntry::factory()->create(['user_id' => $other->id]);

    $this->actingAs($owner)->get(route('entries.show', $foreignEntry))->assertForbidden();
    $this->actingAs($owner)->get(route('entries.edit', $foreignEntry))->assertForbidden();
    $this->actingAs($owner)->get(route('entries.dossier', $foreignEntry))->assertForbidden();
    $this->actingAs($owner)->get(route('entries.export-json', $foreignEntry))->assertForbidden();
});

it('only exposes owned entries through the api', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    BestiaryEntry::factory()->create(['user_id' => $owner->id, 'title' => 'API propia']);
    $foreignEntry = BestiaryEntry::factory()->create(['user_id' => $other->id, 'title' => 'API ajena']);

    Sanctum::actingAs($owner);

    $this->getJson('/api/entries')
        ->assertOk()
        ->assertSee('API propia')
        ->assertDontSee('API ajena');

    $this->getJson('/api/entries/'.$foreignEntry->id)->assertForbidden();
});

it('protects published slug views so only the creator can open them', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $entry = BestiaryEntry::factory()->create([
        'user_id' => $owner->id,
        'slug' => 'published-private-entry',
        'published_at' => now(),
    ]);

    $this->get(route('public.entries.show', $entry->slug))->assertRedirect(route('login'));
    $this->actingAs($other)->get(route('public.entries.show', $entry->slug))->assertForbidden();
    $this->actingAs($owner)->get(route('public.entries.show', $entry->slug))->assertOk();
});
