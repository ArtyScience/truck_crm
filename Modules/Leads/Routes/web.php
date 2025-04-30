<?php

use Illuminate\Support\Facades\Route;
use Modules\Leads\Http\Controllers\LeadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('leads')->group(function() {

    Route::get('/', [LeadController::class , 'index'])
        ->middleware(['auth', 'verified'])
        ->name('leads');

});

