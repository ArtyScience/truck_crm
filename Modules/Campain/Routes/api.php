<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Campain\Http\Controllers\CampainController;

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

    Route::get('campaign/get-statistics/{campaign_id}', [CampainController::class, 'getCampaignStatistics']);
    Route::post('campaign/start-campaign', [CampainController::class, 'startCampaign']);
    Route::post('campaign/update-status', [CampainController::class, 'updateCampaignStatus']);
    Route::get('campaign/get-scheduller/{campaign_id}',
        [CampainController::class, 'getCampaignScheduller']);
    Route::post('campaign/store-scheduller', [CampainController::class, 'storeCampaignScheduller']);
    Route::post('campaign/attach-email', [CampainController::class, 'attachEmailToCampaign']);
    Route::get('campaign/get-template/{campaign_id}', [CampainController::class, 'getCampaignTemplate']);
    Route::post('campaign/save-template', [CampainController::class, 'saveCampaignTemplate']);
    Route::get('campaign/get-leads/{id}', [CampainController::class, 'geCampaignLeads']);
    Route::post('campaign/save-leads', [CampainController::class, 'saveCampaignLeads'])->name('save.leads');

    Route::delete('campaign/remove-campaign-lead/{id}/{campaign_id}',
        [CampainController::class, 'removeCampaignLead']);

    Route::resource('campaign', CampainController::class);
});

