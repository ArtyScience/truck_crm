<?php

use Illuminate\Support\Facades\Route;
use Modules\Deal\Http\Controllers\DealController;

Route::prefix('deal')->group(function() {
    Route::get('/', [DealController::class , 'index'])
        ->middleware(['auth', 'verified'])
        ->name('deal');
});
