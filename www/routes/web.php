<?php

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\VoyageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('voyages', VoyageController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/voyages/{voyage}/participants', [ParticipantController::class, 'store'])
        ->name('participants.store');

    Route::patch('/participants/{participant}/autoriser', [ParticipantController::class, 'autoriser'])
        ->name('participants.autoriser');

    Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy'])
        ->name('participants.destroy');

    Route::post('/voyages/{voyage}/inscription', [ParticipantController::class, 'inscription'])->name('participants.inscription');

    Route::delete('/voyages/{voyage}/inscription', [ParticipantController::class, 'desinscription'])->name('participants.desinscription');
});

require __DIR__.'/auth.php';
