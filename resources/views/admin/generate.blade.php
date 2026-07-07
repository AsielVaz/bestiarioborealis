<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-amber-100">Crea tu creatura</h2></x-slot>
    <form method="POST" action="{{ route('generate.store') }}" class="space-y-4 p-6 text-stone-100">
        @csrf
        <div class="arcane-card rounded-lg p-5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('bestiary-assets/symbol_eye_01.png') }}" alt="" class="h-12 w-12 object-contain opacity-80">
                <h3 class="font-semibold text-cyan-100">Crea tu creatura</h3>
            </div>
            <div class="arcane-divider"></div>
            <textarea name="description" rows="12" class="w-full rounded border-stone-700 bg-stone-950" placeholder="Describe criatura, personaje, PNJ o jefe..."></textarea>
            <button class="mt-4 rounded bg-cyan-400 px-4 py-2 font-semibold text-stone-950">Generar con DeepSeek Chat</button>
        </div>
    </form>
</x-app-layout>
