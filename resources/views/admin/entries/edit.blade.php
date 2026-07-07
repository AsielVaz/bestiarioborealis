<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Editar {{ $entry->title }}</h2></x-slot>
    <form method="POST" action="{{ route('entries.update', $entry) }}" class="space-y-5 p-6 text-stone-100">
        @csrf @method('PUT')
        @include('admin.entries._form')
        <button class="rounded bg-amber-500 px-4 py-2 font-semibold text-stone-950">Guardar</button>
        <a href="{{ route('entries.dossier', $entry) }}" class="ml-2 rounded border border-amber-500/40 px-4 py-2">Ver dossier</a>
    </form>
</x-app-layout>
