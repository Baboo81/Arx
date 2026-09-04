<x-guest-layout>

    {{--
        ============================================================
        CHALLENGE D'AUTHENTIFICATION À DEUX FACTEURS
        ============================================================

        Cette vue est affichée par Laravel Fortify après validation
        de l'email et du mot de passe lorsque le compte possède
        une authentification à deux facteurs active.

        L'utilisateur doit saisir le code TOTP à 6 chiffres généré
        par son application d'authentification.

        Le formulaire est envoyé à la route Fortify :
            POST /two-factor-challenge

        Fortify vérifie le code avant d'autoriser définitivement
        l'ouverture de la session.
    --}}

    <div class="mb-4 text-sm text-gray-600">

        {{ __('Votre compte ARX est protégé par une authentification à deux facteurs.') }}

        <br>

        {{ __('Saisissez le code à 6 chiffres généré par votre application d’authentification.') }}

    </div>


    {{--
        Affichage des éventuelles erreurs retournées par Fortify,
        par exemple lorsqu'un code TOTP est incorrect ou expiré.
    --}}

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />


    <form method="POST" action="{{ route('two-factor.login.store') }}">

        {{-- Protection CSRF de la requête POST --}}
        @csrf


        {{-- Code TOTP généré par l'application d'authentification --}}
        <div>

            <x-input-label
                for="code"
                :value="__('Code d’authentification')"
            />

            <x-text-input
                id="code"
                class="block mt-1 w-full"
                type="text"
                name="code"
                inputmode="numeric"
                autocomplete="one-time-code"
                autofocus
            />

            {{--
                Fortify associe les erreurs de validation du code
                au champ "code".
            --}}

            <x-input-error
                :messages="$errors->get('code')"
                class="mt-2"
            />

        </div>


        <div class="flex items-center justify-end mt-4">

            <x-primary-button>
                {{ __('Se connecter') }}
            </x-primary-button>

        </div>

    </form>

</x-guest-layout>