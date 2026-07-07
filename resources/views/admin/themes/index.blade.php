<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Temas visuales</h2></x-slot>
    <div class="space-y-4 p-6 text-stone-100">
        <div class="flex justify-end"><a href="{{ route('themes.create') }}" class="rounded bg-amber-500 px-3 py-2 text-sm font-semibold text-stone-950">Nuevo tema</a></div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($themes as $theme)
                <article class="arcane-card rounded-lg p-5">
                    <div class="mb-3 flex gap-2">
                        <span class="h-6 w-6 rounded" style="background: {{ $theme->primary_color }}"></span>
                        <span class="h-6 w-6 rounded" style="background: {{ $theme->accent_color }}"></span>
                        <span class="h-6 w-6 rounded" style="background: {{ $theme->parchment_tone }}"></span>
                    </div>
                    <h3 class="font-semibold text-amber-200">{{ $theme->name }}</h3>
                    <p class="text-sm text-stone-400">{{ $theme->key }} / {{ $theme->entries_count }} entradas</p>
                    <a href="{{ route('themes.edit', $theme) }}" class="mt-4 inline-block rounded border border-amber-500/40 px-3 py-2 text-sm">Editar</a>
                </article>
            @endforeach
        </div>
        {{ $themes->links() }}
    </div>
</x-app-layout>
