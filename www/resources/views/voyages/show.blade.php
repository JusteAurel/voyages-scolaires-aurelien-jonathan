<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Détail du voyage
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">

            <div class="bg-white shadow rounded p-6">

                <p><strong>Destination :</strong> {{ $voyage->destination }}</p>

                <p><strong>Date de départ :</strong> {{ $voyage->date_depart }}</p>

                <p><strong>Date de retour :</strong> {{ $voyage->date_retour }}</p>

                <p><strong>Places maximum :</strong> {{ $voyage->places_max }}</p>

                <div class="mt-6">
                    <a href="{{ route('voyages.index') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded">
                        Retour
                    </a>
                </div>

            </div>

        </div>
    </div>
    @if(auth()->user()->role === 'admin')
        <h3 class="text-lg font-semibold mt-6 mb-3">
            Inscrire un participant
        </h3>

        <form
            action="{{ route('participants.store',$voyage) }}"
            method="POST">

            @csrf

            <select
                name="user_id"
                class="border rounded p-2">

                @foreach(\App\Models\User::all() as $user)

                    <option value="{{ $user->id }}">

                        {{ $user->name }}

                    </option>

                @endforeach

            </select>

            <button
                class="bg-blue-600 text-white px-4 py-2 rounded">

                Inscrire

            </button>

        </form>

    @else

        @php
            $estInscrit = $voyage->participants->contains('user_id', auth()->id());
        @endphp

        @if(!$estInscrit)

            <form action="{{ route('participants.inscription', $voyage) }}" method="POST">

                @csrf

                <button class="bg-green-600 text-white px-4 py-2 rounded">
                    M'inscrire au voyage
                </button>

            </form>

        @else

            <p class="mt-6 text-green-600 font-semibold">
                ✅ Vous êtes inscrit à ce voyage.
            </p>

        @endif

            <form method="POST"
                action="{{ route('participants.desinscription', $voyage) }}">
                @csrf
                @method('DELETE')

                <button class="bg-red-600 text-white px-4 py-2 rounded">
                    Se désinscrire
                </button>
            </form>

    @endif

    <hr class="my-6">

    <h3 class="text-lg font-semibold mb-4">
        Participants
    </h3>

    @if($voyage->participants->isEmpty())
        <p>Aucun participant inscrit.</p>
    @else

    <table class="w-full border">

        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Nom</th>
                <th class="p-2 text-left">Email</th>
                <th class="p-2 text-center">Autorisation</th>
                <th class="p-2 text-center">Action</th>
            </tr>
        </thead>

        <tbody>

        @foreach($voyage->participants as $participant)

            <tr class="border-t">

                <td class="p-2">
                    {{ $participant->user->name }}
                </td>

                <td class="p-2">
                    {{ $participant->user->email }}
                </td>

                <td class="text-center">

                    @if($participant->autorisation_parent)

                        ✅ Oui

                    @else

                        ❌ Non

                    @endif

                </td>

                <td class="text-center">

                    <form action="{{ route('participants.autoriser', $participant) }}"
                        method="POST"
                        class="inline">

                        @csrf
                        @method('PATCH')

                        <button
                            class="{{ $participant->autorisation_parent
                                ? 'bg-yellow-500 hover:bg-yellow-600'
                                : 'bg-green-600 hover:bg-green-700' }}
                                text-white px-3 py-1 rounded">

                            {{ $participant->autorisation_parent
                                ? 'Retirer l\'autorisation'
                                : 'Autoriser' }}

                        </button>

                    </form>

                    <form
                        action="{{ route('participants.destroy', $participant) }}"
                        method="POST"
                        class="inline">

                        @csrf
                        @method('DELETE')

                        <button
                            onclick="return confirm('Retirer ce participant ?')"
                            class="bg-red-600 text-white px-3 py-1 rounded">

                            Retirer

                        </button>

                    </form>

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    @endif
</x-app-layout>