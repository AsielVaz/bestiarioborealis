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
    <article class="dossier-sheet arcane-main mx-auto max-w-5xl rounded-lg border-4 p-8 shadow-2xl" style="background: {{ $theme?->parchment_tone ?? '#ead8b7' }}; color: {{ $theme?->ink_color ?? '#21180f' }}; border-color: {{ $theme?->accent_color ?? '#d6ad60' }}">
        <p class="text-sm uppercase tracking-widest">{{ $entry->classification }} / {{ $entry->threat_level }}</p>
        <h1 class="mt-2 text-4xl font-black">{{ $entry->title }}</h1>
        @if($entry->subtitles->isNotEmpty())
            <p class="mt-2 text-lg italic">{{ $entry->subtitles->pluck('value')->implode(' · ') }}</p>
        @endif
        <img src="{{ asset('bestiary-assets/divider_01.png') }}" alt="" class="mx-auto my-4 h-10 w-full object-contain opacity-70">
        <p class="mt-4 text-lg">{{ $entry->description }}</p>

        <div class="mt-6 grid gap-5 md:grid-cols-2">
            @foreach(['abilities' => 'Habilidades', 'techniques' => 'Tecnicas'] as $relation => $label)
                @if($entry->{$relation}->isNotEmpty())
                    <section>
                        <h2 class="font-black">{{ $label }}</h2>
                        @foreach($entry->{$relation} as $item)
                            <p class="mt-2"><strong>{{ $item->name }}:</strong> {{ $item->description }}</p>
                        @endforeach
                    </section>
                @endif
            @endforeach
        </div>

        @if($entry->stats->isNotEmpty())
            <section class="mt-6">
                <h2 class="font-black">Estadisticas</h2>
                <div class="mt-3 grid gap-3 md:grid-cols-3">
                    @foreach($entry->stats as $stat)
                        <p class="rounded border border-current/20 p-3"><strong>{{ $stat->name }}:</strong> {{ $stat->value }}/100 {{ $stat->value_label }}</p>
                    @endforeach
                </div>
            </section>
        @endif

        @if($entry->final_combat_scenario)
            <section class="mt-6">
                <h2 class="font-black">Escenario de combate final</h2>
                <p class="mt-2">{{ $entry->final_combat_scenario }}</p>
            </section>
        @endif
    </article>
</body>
</html>
