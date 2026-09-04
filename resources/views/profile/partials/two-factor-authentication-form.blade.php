<section>

    {{--
        ============================================================
        AUTHENTIFICATION À DEUX FACTEURS (2FA)
        ============================================================

        Cette section permet à l'utilisateur connecté d'activer
        l'authentification à deux facteurs de Laravel Fortify.

        Le processus se déroule en plusieurs étapes :

        1. L'utilisateur demande l'activation du 2FA.
        2. Fortify génère un secret 2FA et des recovery codes.
        3. ARX affiche un QR code contenant les informations nécessaires.
        4. L'utilisateur scanne ce QR code avec son application
           d'authentification.
        5. Il saisit le code TOTP généré par l'application.
        6. Fortify vérifie le code et confirme définitivement le 2FA.
    --}}

    <header>

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Authentification à deux facteurs') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ajoutez une couche de sécurité supplémentaire à votre compte ARX.') }}
        </p>

    </header>


    <div class="mt-6">

        {{--
            ============================================================
            ÉTAPE 1 : DEMANDE D'ACTIVATION DU 2FA
            ============================================================

            Le formulaire envoie une requête POST vers la route Fortify :

                POST /user/two-factor-authentication

            La route "two-factor.enable" est fournie directement
            par Laravel Fortify.

            Fortify se charge ensuite de générer :
            - le secret 2FA ;
            - les codes de récupération.

            Comme confirmPassword est activé dans config/fortify.php,
            Fortify peut demander une confirmation du mot de passe
            avant d'autoriser cette opération sensible.
        --}}

        <form method="POST" action="{{ route('two-factor.enable') }}">

            {{-- Protection CSRF obligatoire pour une requête POST Laravel --}}
            @csrf

            <x-primary-button>
                {{ __('Activer le 2FA') }}
            </x-primary-button>

        </form>


        {{--
            ============================================================
            ÉTAPES 2 ET 3 : CONFIGURATION ET CONFIRMATION DU 2FA
            ============================================================

            Cette partie n'est affichée que si :

            1. Un secret 2FA existe déjà pour l'utilisateur.
            2. Le 2FA n'a pas encore été confirmé.

            two_factor_secret présent :
                Fortify a préparé le 2FA.

            two_factor_confirmed_at = null :
                l'utilisateur n'a pas encore validé un code TOTP.

            Tant que ces deux conditions sont réunies, ARX affiche
            le QR code ainsi que le formulaire de confirmation.
        --}}

        @if (
            auth()->user()->two_factor_secret &&
            is_null(auth()->user()->two_factor_confirmed_at)
        )

            <div class="mt-6">

                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Scannez ce QR code avec votre application d’authentification') }}
                </h3>


                {{--
                    Génération du QR code par Fortify.

                    twoFactorQrCodeSvg() retourne directement le code SVG
                    représentant le QR code.

                    Les {!! !!} sont utilisées volontairement ici afin
                    que Blade interprète le SVG comme du HTML au lieu
                    de l'échapper comme du simple texte.
                --}}

                <div class="mt-4">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>


                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Saisissez ensuite le code à 6 chiffres généré par votre application.') }}
                </p>


                {{--
                    ====================================================
                    ÉTAPE 4 : CONFIRMATION DU CODE TOTP
                    ====================================================

                    L'utilisateur saisit le code généré par son
                    application d'authentification.

                    Le formulaire envoie ce code à la route Fortify :

                        POST /user/confirmed-two-factor-authentication

                    Fortify vérifie alors que le code correspond bien
                    au secret 2FA associé au compte.
                --}}

                <form
                    method="POST"
                    action="{{ route('two-factor.confirm') }}"
                    class="mt-4"
                >

                    @csrf

                    <div>

                        <x-input-label
                            for="code"
                            :value="__('Code de vérification')"
                        />

                        {{--
                            inputmode="numeric"
                                indique notamment aux appareils mobiles
                                qu'un clavier numérique est préférable.

                            autocomplete="one-time-code"
                                indique au navigateur qu'il s'agit d'un
                                code d'authentification temporaire.
                        --}}

                        <x-text-input
                            id="code"
                            name="code"
                            type="text"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />

                        {{--
                            Affiche le message de validation retourné
                            par Fortify si le code saisi est incorrect.
                        --}}

                        <x-input-error
                            :messages="$errors->get('code')"
                            class="mt-2"
                        />

                    </div>


                    <div class="mt-4">

                        <x-primary-button>
                            {{ __('Confirmer le 2FA') }}
                        </x-primary-button>

                    </div>

                </form>

            </div>

        @endif

    </div>

</section>