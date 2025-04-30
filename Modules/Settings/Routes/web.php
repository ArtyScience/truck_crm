<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Logic\Enums\RoleEnum;
use Modules\Settings\Http\Controllers\SettingsController;

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

//get default roles from enum





Route::group([], function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
});
