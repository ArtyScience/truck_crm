<?php

use Modules\User\Http\Controllers\UserController;

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

/* TODO: IMPLEMENT SANCTUM HERE */
Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('/user/all', [UserController::class, 'all']);
    Route::resource('/user', 'UserController');
});


//Route::any('/test', function (Request $request) {
//    dd($request);
//});
