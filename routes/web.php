<?php

use Illuminate\Support\Facades\Route,
    Illuminate\Support\Facades\Redirect,
    App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Redirect::to('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
