<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Liste des voyages
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Tous les voyages</h3>
                    
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('voyages.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Nouveau voyage
                    </a>
                    @endif
                </div>

                @if($voyages->isEmpty())
                    <p>Aucun voyage enregistré.</p>
                @else

                    <table class="w-full border-collapse">

                        <thead>
                            <tr class="border-b">
                                <th class="text-left p-2">Destination</th>
                                <th class="text-left p-2">Départ</th>
                                <th class="text-left p-2">Retour</th>
                                <th class="text-left p-2">Places</th>
                                <th class="text-center p-2">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($voyages as $voyage)

                            <tr class="border-b">

                                <td class="p-2">{{ $voyage->destination }}</td>

                                <td class="p-2">{{ $voyage->date_depart }}</td>

                                <td class="p-2">{{ $voyage->date_retour }}</td>

                                <td class="p-2">{{ $voyage->places_max }}</td>

                                @if(auth()->user()->role === 'admin')
                                <td class="p-2 text-center">

                                    <a href="{{ route('voyages.show',$voyage) }}"
                                       class="text-blue-600">
                                        Voir
                                    </a>

                                    |

                                    <a href="{{ route('voyages.edit',$voyage) }}"
                                       class="text-orange-600">
                                        Modifier
                                    </a>

                                    |

                                    <form action="{{ route('voyages.destroy',$voyage) }}"
                                          method="POST"
                                          class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button class="text-red-600"
                                                onclick="return confirm('Supprimer ce voyage ?')">
                                            Supprimer
                                        </button>

                                    </form>

                                </td>
                                @endif
                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>