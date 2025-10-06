<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Invitation Token (hidden) -->
        @if(request()->query('invitation_token'))
            <input type="hidden" name="invitation_token" value="{{ request()->query('invitation_token') }}">
        @endif

        <!-- Invito Info Banner -->
        @if(isset($invitation) && $invitation)
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                <p class="font-semibold mb-2">✉️ Stai accettando un invito</p>
                <p class="text-sm">Azienda: <strong>{{ $invitation->company_name }}</strong></p>
                <p class="text-sm">Email invito: <strong>{{ $invitation->email }}</strong></p>
                <p class="text-sm text-gray-600 mt-2">Completa la registrazione per creare il tuo account amministratore.</p>
            </div>
        @endif

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email', $invitation->email ?? '')"
                :readonly="isset($invitation) && $invitation"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            @if(isset($invitation) && $invitation)
                <p class="mt-1 text-sm text-gray-500">Email pre-compilata dall'invito (non modificabile)</p>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
