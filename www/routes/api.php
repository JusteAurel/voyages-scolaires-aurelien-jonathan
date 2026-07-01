<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoyageApiController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::post('/login', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {

        return response()->json([
            'message' => 'Identifiants invalides.'
        ], 401);
    }

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
        'user' => $user,
    ]);
});

Route::middleware('auth:sanctum')
    ->name('api.')
    ->group(function () {

        Route::apiResource('voyages', VoyageApiController::class);

        Route::get(
            '/voyages/{voyage}/participants',
            [VoyageApiController::class, 'participants']
        )->name('voyages.participants');
    });