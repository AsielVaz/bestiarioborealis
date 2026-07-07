<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-amber-100">Bestiario Borealis Admin</h2>
            <div class="flex gap-2">
                <a href="{{ route('entries.create') }}" class="rounded bg-amber-500 px-3 py-2 text-sm font-semibold text-stone-950">Nueva entrada</a>
                <a href="{{ route('import.create') }}" class="rounded border border-amber-500/50 px-3 py-2 text-sm text-amber-100">Importar JSON</a>
                <a href="{{ route('generate.create') }}" class="rounded border border-cyan-400/50 px-3 py-2 text-sm text-cyan-100">Generar IA</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 p-6 text-stone-100">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="arcane-card rounded-lg p-5">
                <p class="text-sm text-stone-400">Entradas totales</p>
                <p class="mt-2 text-4xl font-bold text-amber-300">{{ $totalEntries }}</p>
            </div>
            <div class="arcane-card rounded-lg p-5 md:col-span-2">
                <p class="text-sm text-stone-400">Amenazas</p>
                <div class="arcane-divider"></div>
                <div class="mt-3 flex flex-wrap gap-2">
                    @forelse($byThreat as $label => $total)
                        <span class="rounded border border-stone-700 px-3 py-1 text-sm">{{ $label }}: {{ $total }}</span>
                    @empty
                        <span class="text-sm text-stone-500">Sin registros</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="arcane-card rounded-lg p-5">
                <h3 class="font-semibold text-amber-200">Entradas por tema</h3>
                <div class="mt-4 space-y-3">
                    @foreach($byTheme as $theme)
                        <div class="flex items-center justify-between border-b border-stone-800 pb-2">
                            <span class="arcane-marker">{{ $theme->name }}</span>
                            <span class="text-amber-300">{{ $theme->entries_count }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
            <section class="arcane-card rounded-lg p-5">
                <h3 class="font-semibold text-amber-200">Ultimas fichas editadas</h3>
                <div class="mt-4 space-y-3">
                    @forelse($latestEntries as $entry)
                        <a href="{{ route('entries.show', $entry) }}" class="block border-b border-stone-800 pb-2 hover:text-amber-200">
                            {{ $entry->title }} <span class="text-sm text-stone-500">{{ $entry->updated_at->diffForHumans() }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-stone-500">Aun no hay fichas.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
