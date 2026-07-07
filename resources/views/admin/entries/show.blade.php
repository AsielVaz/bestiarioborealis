<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">{{ $entry->title }}</h2></x-slot>
    <div class="space-y-5 p-6 text-stone-100">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('entries.edit', $entry) }}" class="rounded bg-amber-500 px-3 py-2 text-sm font-semibold text-stone-950">Editar</a>
            <a href="{{ route('entries.dossier', $entry) }}" class="rounded border border-amber-500/40 px-3 py-2 text-sm">Dossier</a>
            <a href="{{ route('entries.export-json', $entry) }}" class="rounded border border-stone-700 px-3 py-2 text-sm">Exportar JSON</a>
        </div>
        <article class="arcane-card rounded-lg p-6">
            <p class="text-sm text-amber-300">{{ $entry->classification }} / {{ $entry->threat_level }}</p>
            <div class="arcane-divider"></div>
            <p class="mt-4 text-stone-300">{{ $entry->description }}</p>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach(['abilities' => 'Habilidades', 'techniques' => 'Tecnicas', 'weaknesses' => 'Debilidades'] as $relation => $label)
                    <section><h3 class="font-semibold text-amber-200">{{ $label }}</h3><ul class="mt-2 list-disc pl-5 text-sm text-stone-300">@foreach($entry->{$relation} as $item)<li>{{ $item->name ?? $item->description }}</li>@endforeach</ul></section>
                @endforeach
            </div>
        </article>
    </div>
</x-app-layout>
