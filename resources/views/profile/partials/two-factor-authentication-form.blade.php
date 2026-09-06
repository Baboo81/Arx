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
        ÉTAT 1 : LE 2FA N'EST PAS ENCORE ACTIVÉ
        ============================================================
    --}}
        @if (!auth()->user()->two_factor_secret)
            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf

                <x-primary-button>
                    {{ __('Activer le 2FA') }}
                </x-primary-button>
            </form>


            {{--
        ============================================================
        ÉTAT 2 : LE 2FA EST EN COURS DE CONFIGURATION
        ============================================================

        Le secret existe, mais aucun code TOTP n'a encore été
        confirmé.
    --}}
        @elseif (is_null(auth()->user()->two_factor_confirmed_at))
            <div>
                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Scannez ce QR code avec votre application d’authentification') }}
                </h3>

                <div class="mt-4">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>

                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Saisissez ensuite le code à 6 chiffres généré par votre application.') }}
                </p>

                <form method="POST" action="{{ route('two-factor.confirm') }}" class="mt-4">
                    @csrf

                    <div>
                        <x-input-label for="code" :value="__('Code de vérification')" />

                        <x-text-input id="code" name="code" type="text" inputmode="numeric"
                            autocomplete="one-time-code" class="mt-1 block w-full" required autofocus />

                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-primary-button>
                            {{ __('Confirmer le 2FA') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>


            {{--
        ============================================================
        ÉTAT 3 : LE 2FA EST ACTIVÉ ET CONFIRMÉ
        ============================================================
    --}}
        @else
            <div>
                <p class="text-sm text-green-600 dark:text-green-400">
                    {{ __('L’authentification à deux facteurs est activée sur votre compte.') }}
                </p>

                {{-- ================================================
             CODES DE RÉCUPÉRATION
             ================================================ --}}

                <div class="mt-6">
                    <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Codes de récupération') }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Ces codes permettent d’accéder à votre compte si vous perdez l’accès à votre application d’authentification.') }}
                    </p>

                    <button type="button" id="show-recovery-codes"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600">
                        {{ __('Afficher les codes') }}
                    </button>

                    <div id="recovery-codes" class="mt-4 hidden text-sm text-gray-800 dark:text-gray-200">
                        <p>
                            {{ __('Chargement des codes...') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}" class="mt-4">
                        @csrf

                        <x-secondary-button type="submit">
                            {{ __('Régénérer les codes') }}
                        </x-secondary-button>
                    </form>
                </div>

                {{-- ================================================
             DÉSACTIVATION DU 2FA
             ================================================ --}}

                <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <x-danger-button>
                        {{ __('Désactiver le 2FA') }}
                    </x-danger-button>
                </form>
            </div>
        @endif

    </div>

    {{--
    ============================================================
    AFFICHAGE DES CODES DE RÉCUPÉRATION
    ============================================================

    Ce script permet d'afficher les codes de récupération 2FA
    uniquement lorsque l'utilisateur clique sur le bouton prévu.

    Une requête HTTP est envoyée à la route Fortify
    "two-factor.recovery-codes" via fetch().

    Fortify retourne les codes au format JSON, puis JavaScript
    les insère dans la page sans nécessiter son rechargement.

    Les codes sont ajoutés avec textContent afin qu'ils soient
    interprétés uniquement comme du texte et non comme du HTML.
--}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('show-recovery-codes');
            const container = document.getElementById('recovery-codes');

            if (!button || !container) {
                return;
            }

            button.addEventListener('click', async function() {
                try {
                    const response = await fetch(
                        "{{ route('two-factor.recovery-codes') }}", {
                            headers: {
                                'Accept': 'application/json',
                            }
                        }
                    );

                    if (!response.ok) {
                        throw new Error('Impossible de récupérer les codes.');
                    }

                    const codes = await response.json();

                    container.innerHTML = '';

                    const list = document.createElement('ul');

                    codes.forEach(function(code) {
                        const item = document.createElement('li');
                        item.textContent = code;
                        list.appendChild(item);
                    });

                    container.appendChild(list);
                    container.classList.remove('hidden');

                    button.textContent = 'Masquer les codes';

                } catch (error) {
                    container.innerHTML =
                        '<p>Impossible d’afficher les codes de récupération.</p>';

                    container.classList.remove('hidden');
                }
            });
        });
    </script>

</section>
