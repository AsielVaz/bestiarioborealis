<?php

namespace Database\Seeders;

use App\Models\BestiaryEntry;
use App\Models\DossierTheme;
use App\Models\User;
use Illuminate\Database\Seeder;

class BestiaryEntrySeeder extends Seeder
{
    public function run(): void
    {
        $entry = BestiaryEntry::updateOrCreate(
            ['slug' => 'borealis-examplitus'],
            [
                'user_id' => User::first()?->id,
                'dossier_theme_id' => DossierTheme::where('key', 'arcane')->value('id'),
                'title' => 'Borealis Examplitus',
                'classification' => 'Quimera didactica',
                'category' => 'Demo',
                'threat_level' => 'Media',
                'height' => '2.4 m',
                'description' => 'Entidad de ejemplo usada por eruditos para validar expedientes, rituales de importacion y sincronizacion movil.',
                'last_record' => 'Avistada entre anaqueles cubiertos de escarcha violeta.',
                'status' => 'borrador',
                'final_combat_scenario' => 'El archivo entero se ilumina cuando la criatura invoca duplicados de tinta.',
            ]
        );

        $entry->origin()->updateOrCreate([], [
            'universe' => 'Bestiario Borealis',
            'game' => 'Archivo Arcano',
            'campaign' => 'Catalogo inicial',
            'source' => 'Seeder',
            'region' => 'Sala Norte',
        ]);

        foreach (['subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes'] as $relation) {
            $entry->{$relation}()->delete();
        }

        foreach (['El prototipo del archivo', 'Vigilante de paginas frias'] as $i => $value) {
            $entry->subtitles()->create(['value' => $value, 'sort_order' => $i]);
        }

        foreach (['affinities' => ['Arcano', 'Hielo menor'], 'habitats' => ['Bibliotecas selladas'], 'behaviors' => ['Ordena documentos por amenaza']] as $relation => $items) {
            foreach ($items as $i => $value) {
                $entry->{$relation}()->create(['value' => $value, 'sort_order' => $i]);
            }
        }

        $entry->abilities()->create(['name' => 'Indice viviente', 'description' => 'Localiza debilidades registradas en fichas cercanas.', 'sort_order' => 0]);
        $entry->techniques()->create(['name' => 'Tajo de tinta fria', 'description' => 'Corta defensas con una estela oscura.', 'sort_order' => 0]);
        $entry->weaknesses()->create(['description' => 'Pierde cohesion al exponerlo a luz sagrada directa.', 'sort_order' => 0]);
        $entry->loot()->create(['name' => 'Pluma glacial', 'description' => 'Ingrediente para tinta de catalogacion.', 'rarity' => 'raro', 'sort_order' => 0]);
        $entry->stats()->create(['name' => 'Arcano', 'value' => 82, 'value_label' => 'Alta', 'sort_order' => 0]);
        $entry->vignettes()->create(['title' => 'Primer avistamiento', 'description' => 'Una silueta hecha de escarcha sobre pergamino.', 'sort_order' => 0]);
        $entry->scholarNotes()->create(['note' => 'Mantener esta ficha como referencia de QA para importaciones JSON.', 'sort_order' => 0]);
    }
}
