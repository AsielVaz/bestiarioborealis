<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $entry->title }} / Bestiario Borealis</title>
    @include('layouts.partials.assets')
</head>
<body class="arcane-shell min-h-screen p-6 text-stone-100">
    @php($theme = $entry->dossierTheme)
    <article class="dossier-sheet arcane-main mx-auto max-w-4xl rounded-lg border-4 p-8 shadow-2xl" style="background: {{ $theme?->parchment_tone ?? '#ead8b7' }}; color: {{ $theme?->ink_color ?? '#21180f' }}; border-color: {{ $theme?->accent_color ?? '#d6ad60' }}">
        <p class="text-sm uppercase tracking-widest">{{ $entry->classification }} / {{ $entry->threat_level }}</p>
        <h1 class="mt-2 text-4xl font-black">{{ $entry->title }}</h1>
        <img src="{{ asset('bestiary-assets/divider_01.png') }}" alt="" class="mx-auto my-4 h-10 w-full object-contain opacity-70">
        <p class="mt-4 text-lg">{{ $entry->description }}</p>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <section><h2 class="font-bold">Habilidades</h2>@foreach($entry->abilities as $item)<p><strong>{{ $item->name }}:</strong> {{ $item->description }}</p>@endforeach</section>
            <section><h2 class="font-bold">Estadisticas</h2>@foreach($entry->stats as $stat)<p>{{ $stat->name }}: {{ $stat->value }}/100 {{ $stat->value_label }}</p>@endforeach</section>
        </div>
    </article>
</body>
</html>
