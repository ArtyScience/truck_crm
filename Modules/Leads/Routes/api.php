<?php
use Illuminate\Support\Facades\Route;
use Modules\Leads\Http\Controllers\LeadController;

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::post('leads/import-leads', [LeadController::class, 'importLeads']);
    Route::get('leads/comodities/{lead_id}', [LeadController::class, 'getLeadComodities']);
    Route::get('leads/comodities', [LeadController::class, 'getComodities']);
    Route::post('leads/comodities/create', [LeadController::class, 'createComodity']);
    Route::get('leads/location/{lead_id}', [LeadController::class, 'getFullAddress']);

    Route::get('leads/deals/get-leads', [LeadController::class, 'getLeads']);
    Route::get('leads/additional-contacts/{id}', [LeadController::class, 'getAdditionalContacts']);

    Route::resource('/leads', LeadController::class);
});
