@php($theme = $entry->dossierTheme)
<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Dossier: {{ $entry->title }}</h2></x-slot>
    <div class="p-6">
        <div class="mb-4 flex gap-2 text-sm">
            <a href="{{ route('entries.export-dossier', [$entry, 'png']) }}" class="rounded border border-amber-500/40 px-3 py-2 text-amber-100">PNG</a>
            <a href="{{ route('entries.export-dossier', [$entry, 'pdf']) }}" class="rounded border border-amber-500/40 px-3 py-2 text-amber-100">PDF</a>
        </div>

        <article class="dossier-sheet mx-auto max-w-5xl rounded-lg border-4 p-8 shadow-2xl" style="background: {{ $theme?->parchment_tone ?? '#ead8b7' }}; color: {{ $theme?->ink_color ?? '#21180f' }}; border-color: {{ $theme?->accent_color ?? '#d6ad60' }}">
            <p class="text-sm uppercase tracking-widest">{{ $entry->classification }} / {{ $entry->threat_level }}</p>
            <h1 class="mt-2 text-4xl font-black">{{ $entry->title }}</h1>

            @if($entry->subtitles->isNotEmpty())
                <p class="mt-2 text-lg italic">{{ $entry->subtitles->pluck('value')->implode(' · ') }}</p>
            @endif

            <img src="{{ asset('bestiary-assets/divider_01.png') }}" alt="" class="mx-auto my-4 h-10 w-full object-contain opacity-70">

            <div class="grid gap-4 md:grid-cols-3">
                <p><strong>Categoria:</strong><br>{{ $entry->category ?? 'Sin categoria' }}</p>
                <p><strong>Altura:</strong><br>{{ $entry->height ?? 'No registrada' }}</p>
                <p><strong>Estado:</strong><br>{{ $entry->status ?? 'Sin estado' }}</p>
            </div>

            <section class="mt-6">
                <h2 class="text-xl font-black">Descripcion</h2>
                <p class="mt-2 text-lg">{{ $entry->description }}</p>
            </section>

            @if($entry->origin)
                <section class="mt-6">
                    <h2 class="text-xl font-black">Origen</h2>
                    <div class="mt-2 grid gap-2 md:grid-cols-2">
                        @foreach(['universe' => 'Universo', 'game' => 'Juego', 'campaign' => 'Campana', 'source' => 'Fuente', 'region' => 'Region'] as $field => $label)
                            @if($entry->origin->{$field})
                                <p><strong>{{ $label }}:</strong> {{ $entry->origin->{$field} }}</p>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endif

            @if($entry->last_record)
                <section class="mt-6">
                    <h2 class="text-xl font-black">Ultimo registro</h2>
                    <p class="mt-2">{{ $entry->last_record }}</p>
                </section>
            @endif

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach([
                    'affinities' => ['Afinidades', 'value'],
                    'habitats' => ['Habitats', 'value'],
                    'behaviors' => ['Comportamientos', 'value'],
                ] as $relation => [$label, $field])
                    @if($entry->{$relation}->isNotEmpty())
                        <section>
                            <h2 class="font-black">{{ $label }}</h2>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach($entry->{$relation} as $item)
                                    <li>{{ $item->{$field} }}</li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                @endforeach
            </div>

            <div class="mt-6 grid gap-5 md:grid-cols-2">
                @foreach(['abilities' => 'Habilidades', 'techniques' => 'Tecnicas'] as $relation => $label)
                    @if($entry->{$relation}->isNotEmpty())
                        <section>
                            <h2 class="font-black">{{ $label }}</h2>
                            <div class="mt-2 space-y-3">
                                @foreach($entry->{$relation} as $item)
                                    <p><strong>{{ $item->name }}:</strong> {{ $item->description }}</p>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>

            <div class="mt-6 grid gap-5 md:grid-cols-2">
                @if($entry->weaknesses->isNotEmpty())
                    <section>
                        <h2 class="font-black">Debilidades</h2>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach($entry->weaknesses as $item)
                                <li>{{ $item->description }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if($entry->loot->isNotEmpty())
                    <section>
                        <h2 class="font-black">Loot</h2>
                        <div class="mt-2 space-y-3">
                            @foreach($entry->loot as $item)
                                <p><strong>{{ $item->name }}</strong> @if($item->rarity)<span>({{ $item->rarity }})</span>@endif<br>{{ $item->description }}</p>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            @if($entry->stats->isNotEmpty())
                <section class="mt-6">
                    <h2 class="font-black">Estadisticas</h2>
                    <div class="mt-3 grid gap-3 md:grid-cols-3">
                        @foreach($entry->stats as $stat)
                            <div class="rounded border border-current/20 p-3">
                                <p class="font-bold">{{ $stat->name }}</p>
                                <p>{{ $stat->value }}/100 {{ $stat->value_label }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($entry->vignettes->isNotEmpty())
                <section class="mt-6">
                    <h2 class="font-black">Vinetas</h2>
                    <div class="mt-2 grid gap-4 md:grid-cols-2">
                        @foreach($entry->vignettes as $item)
                            <div>
                                <h3 class="font-bold">{{ $item->title }}</h3>
                                <p>{{ $item->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($entry->final_combat_scenario)
                <section class="mt-6">
                    <h2 class="text-xl font-black">Escenario de combate final</h2>
                    <p class="mt-2">{{ $entry->final_combat_scenario }}</p>
                </section>
            @endif

            @if($entry->scholarNotes->isNotEmpty())
                <section class="mt-6">
                    <h2 class="text-xl font-black">Notas del erudito</h2>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach($entry->scholarNotes as $note)
                            <li>{{ $note->note }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </article>
    </div>
</x-app-layout>
