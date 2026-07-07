<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Importar JSON</h2></x-slot>
    <form method="POST" action="{{ route('import.store') }}" class="space-y-4 p-6 text-stone-100">
        @csrf
        <div class="arcane-card rounded-lg p-5">
            <button type="button" onclick="navigator.clipboard.writeText('Genera JSON para Bestiario Borealis con title, classification, threat_level, description, abilities, techniques, weaknesses, loot, stats, vignettes y scholar_notes.')" class="rounded border border-amber-500/40 px-3 py-2 text-sm">Copiar prompt</button>
            <div class="arcane-divider"></div>
            <textarea name="json" rows="18" class="w-full rounded border-stone-700 bg-stone-950 font-mono text-sm" placeholder='{"title":"..."}'></textarea>
            <button class="mt-4 rounded bg-amber-500 px-4 py-2 font-semibold text-stone-950">Validar e importar</button>
        </div>
    </form>
</x-app-layout>
