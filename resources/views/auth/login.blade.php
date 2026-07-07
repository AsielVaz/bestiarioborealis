<x-guest-layout>
    <div class="relative">
        <img src="{{ asset('bestiary-assets/corner_tr_eye.png') }}" alt="" class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 object-contain opacity-20">

        <div class="mb-6 text-center">
            <img src="{{ asset('bestiary-assets/seal_square_02.png') }}" alt="" class="mx-auto h-20 w-20 object-contain opacity-90 drop-shadow">
            <p class="mt-3 text-xs uppercase tracking-[0.24em] text-amber-400">Acceso restringido</p>
            <h1 class="mt-2 text-2xl font-semibold text-amber-100">Archivo Borealis</h1>
            <p class="mt-2 text-sm leading-6 text-stone-400">Identificate para abrir los expedientes, custodiar criaturas y consultar dossiers arcanos.</p>
            <div class="arcane-divider"></div>
        </div>

        <x-auth-session-status class="mb-4 text-sm text-emerald-300" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="email" value="Correo del erudito" class="text-stone-200" />
                <x-text-input id="email" class="mt-1 block w-full border-amber-500/20 bg-stone-950/90 text-stone-100 placeholder:text-stone-600 focus:border-amber-400 focus:ring-amber-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nombre@archivo.test" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Sello de acceso" class="text-stone-200" />
                <x-text-input id="password" class="mt-1 block w-full border-amber-500/20 bg-stone-950/90 text-stone-100 placeholder:text-stone-600 focus:border-amber-400 focus:ring-amber-400" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-amber-500/30 bg-stone-950 text-amber-500 shadow-sm focus:ring-amber-500" name="remember">
                    <span class="ms-2 text-sm text-stone-400">Recordar acceso</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="rounded text-sm text-amber-300 underline decoration-amber-500/40 underline-offset-4 hover:text-amber-100" href="{{ route('password.request') }}">
                        Recuperar sello
                    </a>
                @endif
            </div>

            <button class="w-full rounded border border-amber-300/30 bg-amber-500 px-4 py-3 text-sm font-bold uppercase tracking-widest text-stone-950 shadow-lg shadow-amber-950/20 transition hover:bg-amber-400">
                Entrar al archivo
            </button>
        </form>

        <div class="mt-6 rounded-lg border border-cyan-400/20 bg-cyan-950/20 p-4 text-center">
            <p class="text-sm text-stone-300">¿Aun no tienes credenciales?</p>
            <a href="{{ route('register') }}" class="mt-3 inline-flex w-full items-center justify-center rounded border border-cyan-300/40 px-4 py-2 text-sm font-semibold text-cyan-100 transition hover:bg-cyan-300 hover:text-stone-950">
                Solicitar registro de erudito
            </a>
        </div>
    </div>
</x-guest-layout>
