@php
    $entry = $entry ?? null;
    $lineValue = function (string $name, string $relation, string $field = 'value') use ($entry) {
        $old = old($name);
        if (is_array($old)) {
            return collect($old)->map(fn ($item) => is_array($item) ? ($item[$field] ?? '') : $item)->filter()->implode("\n");
        }

        if (is_string($old)) {
            return $old;
        }

        return $entry?->{$relation}?->pluck($field)->implode("\n") ?? '';
    };
    $jsonValue = function (string $name, string $relation, array $fields) use ($entry) {
        $old = old($name);
        if (is_string($old)) {
            return $old;
        }

        $items = is_array($old)
            ? collect($old)
            : ($entry?->{$relation} ?? collect());

        return $items->map(fn ($item) => collect($fields)->mapWithKeys(fn ($field) => [$field => is_array($item) ? ($item[$field] ?? null) : $item->{$field}])->filter(fn ($value) => $value !== null && $value !== '')->all())
            ->values()
            ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    };
@endphp
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
            <input name="origin[source]" value="{{ old('origin.source', $entry?->origin?->source) }}" placeholder="Fuente" class="rounded border-stone-700 bg-stone-900">
            <input name="origin[region]" value="{{ old('origin.region', $entry?->origin?->region) }}" placeholder="Region" class="rounded border-stone-700 bg-stone-900">
            <textarea name="last_record" rows="3" placeholder="Ultimo registro" class="rounded border-stone-700 bg-stone-900">{{ old('last_record', $entry?->last_record) }}</textarea>
        </div>
    </section>
    <section class="arcane-card rounded-lg p-5 lg:col-span-2">
        <h3 class="mb-4 font-semibold text-amber-200">Rasgos y comportamiento</h3>
        <div class="grid gap-4 md:grid-cols-3">
            <textarea name="subtitles" rows="4" placeholder="Subtitulos: una linea por elemento" class="rounded border-stone-700 bg-stone-900">{{ $lineValue('subtitles', 'subtitles') }}</textarea>
            <textarea name="affinities" rows="4" placeholder="Afinidades: una linea por elemento" class="rounded border-stone-700 bg-stone-900">{{ $lineValue('affinities', 'affinities') }}</textarea>
            <textarea name="habitats" rows="4" placeholder="Habitats: una linea por elemento" class="rounded border-stone-700 bg-stone-900">{{ $lineValue('habitats', 'habitats') }}</textarea>
            <textarea name="behaviors" rows="4" placeholder="Comportamientos: una linea por elemento" class="rounded border-stone-700 bg-stone-900">{{ $lineValue('behaviors', 'behaviors') }}</textarea>
            <textarea name="weaknesses" rows="4" placeholder="Debilidades: una linea por elemento" class="rounded border-stone-700 bg-stone-900">{{ $lineValue('weaknesses', 'weaknesses', 'description') }}</textarea>
            <textarea name="scholar_notes" rows="4" placeholder="Notas del erudito: una linea por elemento" class="rounded border-stone-700 bg-stone-900">{{ $lineValue('scholar_notes', 'scholarNotes', 'note') }}</textarea>
        </div>
    </section>
    <section class="arcane-card rounded-lg p-5 lg:col-span-2">
        <h3 class="mb-4 font-semibold text-amber-200">Secciones estructuradas</h3>
        <p class="mb-4 text-sm text-stone-400">Edita estas secciones como JSON. El formato es el mismo que usa la app movil.</p>
        <div class="grid gap-4 lg:grid-cols-2">
            <label class="grid gap-2 text-sm text-stone-300">Habilidades
                <textarea name="abilities" rows="9" class="rounded border-stone-700 bg-stone-900 font-mono text-xs">{{ $jsonValue('abilities', 'abilities', ['name', 'description']) }}</textarea>
            </label>
            <label class="grid gap-2 text-sm text-stone-300">Tecnicas
                <textarea name="techniques" rows="9" class="rounded border-stone-700 bg-stone-900 font-mono text-xs">{{ $jsonValue('techniques', 'techniques', ['name', 'description']) }}</textarea>
            </label>
            <label class="grid gap-2 text-sm text-stone-300">Loot
                <textarea name="loot" rows="9" class="rounded border-stone-700 bg-stone-900 font-mono text-xs">{{ $jsonValue('loot', 'loot', ['name', 'description', 'rarity']) }}</textarea>
            </label>
            <label class="grid gap-2 text-sm text-stone-300">Estadisticas
                <textarea name="stats" rows="9" class="rounded border-stone-700 bg-stone-900 font-mono text-xs">{{ $jsonValue('stats', 'stats', ['name', 'value', 'value_label']) }}</textarea>
            </label>
            <label class="grid gap-2 text-sm text-stone-300 lg:col-span-2">Vinetas
                <textarea name="vignettes" rows="8" class="rounded border-stone-700 bg-stone-900 font-mono text-xs">{{ $jsonValue('vignettes', 'vignettes', ['title', 'description', 'image_path']) }}</textarea>
            </label>
        </div>
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
