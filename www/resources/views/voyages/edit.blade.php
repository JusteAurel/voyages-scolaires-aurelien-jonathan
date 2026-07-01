<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Modifier un voyage
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-3xl mx-auto">

            <form action="{{ route('voyages.update', $voyage) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label>Destination</label>

                    <input type="text"
                           name="destination"
                           class="w-full border rounded p-2"
                           value="{{ old('destination', $voyage->destination) }}">
                </div>

                <div class="mb-4">
                    <label>Date de départ</label>

                    <input type="date"
                           name="date_depart"
                           class="w-full border rounded p-2"
                           value="{{ old('date_depart', $voyage->date_depart) }}">
                </div>

                <div class="mb-4">
                    <label>Date de retour</label>

                    <input type="date"
                           name="date_retour"
                           class="w-full border rounded p-2"
                           value="{{ old('date_retour', $voyage->date_retour) }}">
                </div>

                <div class="mb-6">
                    <label>Nombre de places</label>

                    <input type="number"
                           name="places_max"
                           class="w-full border rounded p-2"
                           value="{{ old('places_max', $voyage->places_max) }}">
                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Enregistrer
                </button>

            </form>

        </div>
    </div>
</x-app-layout>