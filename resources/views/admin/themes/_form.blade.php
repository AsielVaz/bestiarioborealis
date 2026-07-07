@php($theme = $theme ?? null)
<div class="arcane-card grid gap-4 rounded-lg p-5 md:grid-cols-2">
    <input name="key" value="{{ old('key', $theme?->key) }}" placeholder="key" class="rounded border-stone-700 bg-stone-900" required>
    <input name="name" value="{{ old('name', $theme?->name) }}" placeholder="Nombre" class="rounded border-stone-700 bg-stone-900" required>
    <input type="color" name="primary_color" value="{{ old('primary_color', $theme?->primary_color ?? '#4c1d95') }}" class="h-11 rounded border-stone-700 bg-stone-900">
    <input type="color" name="accent_color" value="{{ old('accent_color', $theme?->accent_color ?? '#d6ad60') }}" class="h-11 rounded border-stone-700 bg-stone-900">
    <input type="color" name="parchment_tone" value="{{ old('parchment_tone', $theme?->parchment_tone ?? '#ead8b7') }}" class="h-11 rounded border-stone-700 bg-stone-900">
    <input name="frame_style" value="{{ old('frame_style', $theme?->frame_style) }}" placeholder="Estilo de marco" class="rounded border-stone-700 bg-stone-900">
    <textarea name="description" rows="4" placeholder="Descripcion" class="rounded border-stone-700 bg-stone-900 md:col-span-2">{{ old('description', $theme?->description) }}</textarea>
    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $theme?->is_active ?? true))> Activo</label>
</div>
