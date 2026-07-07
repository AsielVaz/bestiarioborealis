<x-guest-layout>
    <div class="mb-5 text-center">
        <p class="text-xs uppercase tracking-[0.22em] text-amber-400">Archivo arcano</p>
        <h1 class="mt-2 text-2xl font-semibold text-amber-100">Crear cuenta</h1>
        <div class="arcane-divider"></div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nombre" class="text-stone-200" />
            <x-text-input id="name" class="mt-1 block w-full border-stone-700 bg-stone-950 text-stone-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Correo" class="text-stone-200" />
            <x-text-input id="email" class="mt-1 block w-full border-stone-700 bg-stone-950 text-stone-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Contraseña" class="text-stone-200" />
            <x-text-input id="password" class="mt-1 block w-full border-stone-700 bg-stone-950 text-stone-100" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar contraseña" class="text-stone-200" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full border-stone-700 bg-stone-950 text-stone-100" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="rounded text-sm text-stone-300 underline hover:text-amber-200" href="{{ route('login') }}">
                Ya tengo cuenta
            </a>

            <button class="rounded bg-amber-500 px-4 py-2 text-sm font-semibold text-stone-950 hover:bg-amber-400">
                Registrarme
            </button>
        </div>
    </form>
</x-guest-layout>
