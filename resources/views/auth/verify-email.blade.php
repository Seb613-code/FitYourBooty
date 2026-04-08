<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Merci pour votre inscription ! Avant de commencer, merci de verifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. Si vous n\'avez rien recu, nous pouvons vous renvoyer un email.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Un nouveau lien de verification a ete envoye a l\'adresse email fournie lors de l\'inscription.') }}
        </div>
    @endif

    @if (session('status') == 'verification-link-send-failed')
        <div class="mb-4 font-medium text-sm text-amber-600">
            {{ __('Impossible d\'envoyer l\'email pour le moment. Veuillez reessayer plus tard.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Renvoyer l\'email de verification') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Se deconnecter') }}
            </button>
        </form>
    </div>
</x-guest-layout>
