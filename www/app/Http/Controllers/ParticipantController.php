<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Voyage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function store(Request $request, Voyage $voyage): RedirectResponse
    {
        if(Auth::user()->role !== 'admin'){
            abort(403);
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $dejaInscrit = Participant::where('voyage_id', $voyage->id)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($dejaInscrit) {
            return back()->with('erreur', 'Cet utilisateur est déjà inscrit à ce voyage.');
        }

        Participant::create([
            'voyage_id' => $voyage->id,
            'user_id' => $request->user_id,
        ]);

        return back()->with('success', 'Inscrit au voyage.');
    }

    public function autoriser(Participant $participant): RedirectResponse
    {
        if(Auth::user()->role !== 'admin'){
            abort(403);
        }
        $participant->update([
            'autorisation_parent' => !$participant->autorisation_parent,
        ]);

        return back()->with(
            'success',
            $participant->autorisation_parent
                ? 'Autorisation parentale accordée.'
                : 'Autorisation parentale retirée.'
        );
    }

    public function destroy(Participant $participant): RedirectResponse
    {
        if(Auth::user()->role !== 'admin'){
            abort(403);
        }
        $participant->delete();

        return back()->with('success', 'Participant retiré du voyage.');
    }

    public function inscription(Voyage $voyage)
    {
        $user = Auth::user();

        if ($user->role !== 'eleve') {
            abort(403);
        }

        if ($voyage->participants()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Vous êtes déjà inscrit.');
        }

        if ($voyage->participants()->count() >= $voyage->places_max) {
            return back()->with('error', 'Le voyage est complet.');
        }

        Participant::create([
            'voyage_id' => $voyage->id,
            'user_id' => $user->id,
            'autorisation_parent' => false,
        ]);

        return back()->with('success', 'Inscription effectuée.');
    }

    public function desinscription(Voyage $voyage)
    {
        Participant::where('voyage_id', $voyage->id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Vous êtes désinscrit.');
    }
}