<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voyage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoyageApiController extends Controller
{
    public function index(): JsonResponse
    {
        $voyages = Voyage::withCount('participants')->paginate(15);

        return response()->json($voyages);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'date_depart' => 'required|date',
            'date_retour' => 'required|date|after:date_depart',
            'places_max' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        $voyage = Voyage::create($validated);

        return response()->json($voyage, 201);
    }

    public function show(Voyage $voyage): JsonResponse
    {
        return response()->json(
            $voyage->load('participants.user')
        );
    }

    public function update(Request $request, Voyage $voyage): JsonResponse
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'date_depart' => 'required|date',
            'date_retour' => 'required|date|after:date_depart',
            'places_max' => 'required|integer|min:1',
        ]);

        $voyage->update($validated);

        return response()->json($voyage);
    }

    public function destroy(Voyage $voyage): JsonResponse
    {
        $voyage->delete();

        return response()->json([
            'message' => 'Voyage supprimé.'
        ]);
    }

    public function participants(Voyage $voyage): JsonResponse
    {
        return response()->json(
            $voyage->participants()->with('user')->get()
        );
    }
}