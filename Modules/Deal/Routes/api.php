<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Deal\Http\Controllers\DealController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('deal', fn (Request $request) => $request->user())->name('deal');
    Route::get('deal/get-leads-by-contact/{contact}', [DealController::class, 'getLeadsByContact']);
    Route::post('deal/status/update', [DealController::class, 'statusUpdate'])->name('deal.status_update');

    Route::get('deal/all', [DealController::class, 'all']);

    Route::resource('deal', DealController::class);
});

