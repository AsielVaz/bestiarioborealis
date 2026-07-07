@php($theme = $entry->dossierTheme)
<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Dossier: {{ $entry->title }}</h2></x-slot>
    <div class="p-6">
        <div class="mb-4 flex gap-2 text-sm">
            <a href="{{ route('entries.export-dossier', [$entry, 'png']) }}" class="rounded border border-amber-500/40 px-3 py-2 text-amber-100">PNG</a>
            <a href="{{ route('entries.export-dossier', [$entry, 'pdf']) }}" class="rounded border border-amber-500/40 px-3 py-2 text-amber-100">PDF</a>
        </div>
        <article class="dossier-sheet mx-auto max-w-4xl rounded-lg border-4 p-8 shadow-2xl" style="background: {{ $theme?->parchment_tone ?? '#ead8b7' }}; color: {{ $theme?->ink_color ?? '#21180f' }}; border-color: {{ $theme?->accent_color ?? '#d6ad60' }}">
            <p class="text-sm uppercase tracking-widest">{{ $entry->classification }} / {{ $entry->threat_level }}</p>
            <h1 class="mt-2 text-4xl font-black">{{ $entry->title }}</h1>
            <img src="{{ asset('bestiary-assets/divider_01.png') }}" alt="" class="mx-auto my-4 h-10 w-full object-contain opacity-70">
            <p class="mt-4 text-lg">{{ $entry->description }}</p>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <section><h2 class="font-bold">Habilidades</h2>@foreach($entry->abilities as $item)<p><strong>{{ $item->name }}:</strong> {{ $item->description }}</p>@endforeach</section>
                <section><h2 class="font-bold">Estadisticas</h2>@foreach($entry->stats as $stat)<p>{{ $stat->name }}: {{ $stat->value }}/100 {{ $stat->value_label }}</p>@endforeach</section>
            </div>
        </article>
    </div>
</x-app-layout>
