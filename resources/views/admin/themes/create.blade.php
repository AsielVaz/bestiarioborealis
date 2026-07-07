<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Nuevo tema</h2></x-slot>
    <form method="POST" action="{{ route('themes.store') }}" class="space-y-5 p-6 text-stone-100">
        @csrf
        @include('admin.themes._form')
        <button class="rounded bg-amber-500 px-4 py-2 font-semibold text-stone-950">Guardar</button>
    </form>
</x-app-layout>
