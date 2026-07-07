<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Entradas</h2></x-slot>
    <div class="space-y-4 p-6 text-stone-100">
        <form class="arcane-card grid gap-3 rounded-lg p-4 md:grid-cols-6">
            <input name="search" value="{{ request('search') }}" placeholder="Titulo" class="rounded border-stone-700 bg-stone-900 md:col-span-2">
            <input name="classification" value="{{ request('classification') }}" placeholder="Clasificacion" class="rounded border-stone-700 bg-stone-900">
            <input name="category" value="{{ request('category') }}" placeholder="Categoria" class="rounded border-stone-700 bg-stone-900">
            <input name="threat_level" value="{{ request('threat_level') }}" placeholder="Amenaza" class="rounded border-stone-700 bg-stone-900">
            <button class="rounded bg-amber-500 px-3 py-2 font-semibold text-stone-950">Filtrar</button>
        </form>
        <div class="flex justify-end"><a href="{{ route('entries.create') }}" class="rounded bg-amber-500 px-3 py-2 text-sm font-semibold text-stone-950">Nueva entrada</a></div>
        <div class="arcane-card overflow-hidden rounded-lg">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-900 text-stone-300"><tr><th class="p-3">Titulo</th><th>Clasificacion</th><th>Amenaza</th><th>Tema</th><th>Estado</th></tr></thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr class="border-t border-stone-800">
                            <td class="p-3"><a class="arcane-marker text-amber-200" href="{{ route('entries.show', $entry) }}">{{ $entry->title }}</a></td>
                            <td>{{ $entry->classification }}</td><td>{{ $entry->threat_level }}</td><td>{{ $entry->dossierTheme?->name }}</td><td>{{ $entry->status ?? 'sin estado' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $entries->links() }}
    </div>
</x-app-layout>
