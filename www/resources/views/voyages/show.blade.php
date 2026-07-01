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
</x-app-layout>