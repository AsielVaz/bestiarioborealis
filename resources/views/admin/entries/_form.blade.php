@php($entry = $entry ?? null)
<div class="grid gap-4 lg:grid-cols-2">
    <section class="arcane-card rounded-lg p-5">
        <h3 class="mb-4 font-semibold text-amber-200">Identificacion</h3>
        <div class="arcane-divider"></div>
        <div class="grid gap-3">
            <input name="title" value="{{ old('title', $entry?->title) }}" placeholder="Titulo" class="rounded border-stone-700 bg-stone-900" required>
            <input name="slug" value="{{ old('slug', $entry?->slug) }}" placeholder="Slug publico" class="rounded border-stone-700 bg-stone-900">
            <input name="classification" value="{{ old('classification', $entry?->classification) }}" placeholder="Clasificacion" class="rounded border-stone-700 bg-stone-900" required>
            <input name="category" value="{{ old('category', $entry?->category) }}" placeholder="Categoria" class="rounded border-stone-700 bg-stone-900">
            <input name="threat_level" value="{{ old('threat_level', $entry?->threat_level) }}" placeholder="Nivel de amenaza" class="rounded border-stone-700 bg-stone-900" required>
            <input name="height" value="{{ old('height', $entry?->height) }}" placeholder="Altura" class="rounded border-stone-700 bg-stone-900">
            <textarea name="description" rows="5" placeholder="Descripcion" class="rounded border-stone-700 bg-stone-900" required>{{ old('description', $entry?->description) }}</textarea>
        </div>
    </section>
    <section class="arcane-card rounded-lg p-5">
        <h3 class="mb-4 font-semibold text-amber-200">Origen e imagen</h3>
        <div class="arcane-divider"></div>
        <div class="grid gap-3">
            <select name="dossier_theme_id" class="rounded border-stone-700 bg-stone-900">
                <option value="">Tema visual</option>
                @foreach($themes as $theme)
                    <option value="{{ $theme->id }}" @selected(old('dossier_theme_id', $entry?->dossier_theme_id) == $theme->id)>{{ $theme->name }}</option>
                @endforeach
            </select>
            <input name="main_image_path" value="{{ old('main_image_path', $entry?->main_image_path) }}" placeholder="Ruta Storage de imagen principal" class="rounded border-stone-700 bg-stone-900">
            <input name="origin[universe]" value="{{ old('origin.universe', $entry?->origin?->universe) }}" placeholder="Universo" class="rounded border-stone-700 bg-stone-900">
            <input name="origin[game]" value="{{ old('origin.game', $entry?->origin?->game) }}" placeholder="Juego" class="rounded border-stone-700 bg-stone-900">
            <input name="origin[campaign]" value="{{ old('origin.campaign', $entry?->origin?->campaign) }}" placeholder="Campana" class="rounded border-stone-700 bg-stone-900">
            <textarea name="last_record" rows="3" placeholder="Ultimo registro" class="rounded border-stone-700 bg-stone-900">{{ old('last_record', $entry?->last_record) }}</textarea>
        </div>
    </section>
    <section class="arcane-card rounded-lg p-5 lg:col-span-2">
        <h3 class="mb-4 font-semibold text-amber-200">Listas ordenadas</h3>
        <div class="grid gap-4 md:grid-cols-3">
            @foreach(['subtitles' => 'Subtitulos', 'affinities' => 'Afinidades', 'habitats' => 'Habitats', 'behaviors' => 'Comportamientos', 'weaknesses' => 'Debilidades', 'scholar_notes' => 'Notas del erudito'] as $name => $label)
                <textarea name="{{ $name }}" rows="4" placeholder="{{ $label }}: una linea por elemento" class="rounded border-stone-700 bg-stone-900">{{ old($name) }}</textarea>
            @endforeach
        </div>
        <p class="mt-3 text-sm text-stone-500">Para habilidades, tecnicas, loot, estadisticas y vinetas puedes importar JSON completo desde la pantalla de importacion.</p>
    </section>
    <section class="arcane-card rounded-lg p-5 lg:col-span-2">
        <h3 class="mb-4 font-semibold text-amber-200">Combate final y publicacion</h3>
        <textarea name="final_combat_scenario" rows="4" placeholder="Escenario de combate final" class="w-full rounded border-stone-700 bg-stone-900">{{ old('final_combat_scenario', $entry?->final_combat_scenario) }}</textarea>
        <div class="mt-3 grid gap-3 md:grid-cols-2">
            <input name="status" value="{{ old('status', $entry?->status) }}" placeholder="Estado" class="rounded border-stone-700 bg-stone-900">
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $entry?->published_at?->format('Y-m-d\TH:i')) }}" class="rounded border-stone-700 bg-stone-900">
        </div>
    </section>
</div>
