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

    <x-auth-session-status class="mb-4" :status="session('status')" />


    <form method="POST" action="{{ route('two-factor.login.store') }}">

        @csrf

        {{-- ============================================================
         MODE 1 : CODE TOTP
         ============================================================ --}}
        <div id="totp-section">
            <x-input-label for="code" :value="__('Code d’authentification')" />

            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" inputmode="numeric"
                autocomplete="one-time-code" autofocus />

            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>


        {{-- ============================================================
         MODE 2 : CODE DE RÉCUPÉRATION
         ============================================================ --}}
        <div id="recovery-section" class="hidden">
            <x-input-label for="recovery_code" :value="__('Code de récupération')" />

            <x-text-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code"
                autocomplete="one-time-code" />

            <x-input-error :messages="$errors->get('recovery_code')" class="mt-2" />
        </div>


        {{-- Bascule entre TOTP et recovery code --}}
        <div class="mt-4">
            <button type="button" id="toggle-recovery" class="text-sm text-gray-600 dark:text-gray-400 underline">
                {{ __('Utiliser un code de récupération') }}
            </button>
        </div>


        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Se connecter') }}
            </x-primary-button>
        </div>

    </form>

    {{--
    ============================================================
    BASCULE ENTRE CODE TOTP ET CODE DE RÉCUPÉRATION
    ============================================================

    Ce script permet à l'utilisateur de choisir entre le code
    TOTP généré par son application d'authentification et un
    code de récupération.

    Les deux champs sont présents dans le formulaire, mais un
    seul est affiché à la fois.

    Fortify détermine ensuite automatiquement quel type de code
    a été fourni lors de l'envoi du formulaire.
--}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const toggleButton = document.getElementById('toggle-recovery');
            const totpSection = document.getElementById('totp-section');
            const recoverySection = document.getElementById('recovery-section');

            if (!toggleButton || !totpSection || !recoverySection) {
                return;
            }

            toggleButton.addEventListener('click', function() {

                const recoveryVisible = !recoverySection.classList.contains('hidden');

                if (recoveryVisible) {

                    recoverySection.classList.add('hidden');
                    totpSection.classList.remove('hidden');

                    toggleButton.textContent =
                        'Utiliser un code de récupération';

                } else {

                    totpSection.classList.add('hidden');
                    recoverySection.classList.remove('hidden');

                    toggleButton.textContent =
                        'Utiliser un code d’authentification';
                }
            });
        });
    </script>

</x-guest-layout>
