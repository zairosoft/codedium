<?php

use Illuminate\Support\Facades\Route;
use Modules\Crm\App\Http\Controllers\CrmController;

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

Route::prefix('crm')->name('crm.')->group(function () {
    // Dashboard
    Route::get('/', [CrmController::class, 'index'])->name('index');
    
    // Contacts management
    Route::get('/contacts', [CrmController::class, 'contacts'])->name('contacts');
    Route::get('/contacts/create', [CrmController::class, 'createContact'])->name('contacts.create');
    Route::post('/contacts', [CrmController::class, 'storeContact'])->name('contacts.store');
    Route::get('/contacts/{id}', [CrmController::class, 'showContact'])->name('contacts.show');
    Route::get('/contacts/{id}/edit', [CrmController::class, 'editContact'])->name('contacts.edit');
    Route::put('/contacts/{id}', [CrmController::class, 'updateContact'])->name('contacts.update');
    Route::delete('/contacts/{id}', [CrmController::class, 'deleteContact'])->name('contacts.delete');
    
    // Leads management
    Route::get('/leads', [CrmController::class, 'leads'])->name('leads');
    Route::get('/leads/create', [CrmController::class, 'createLead'])->name('leads.create');
    Route::post('/leads', [CrmController::class, 'storeLead'])->name('leads.store');
    Route::get('/leads/{id}', [CrmController::class, 'showLead'])->name('leads.show');
    Route::get('/leads/{id}/edit', [CrmController::class, 'editLead'])->name('leads.edit');
    Route::put('/leads/{id}', [CrmController::class, 'updateLead'])->name('leads.update');
    Route::delete('/leads/{id}', [CrmController::class, 'deleteLead'])->name('leads.delete');
    Route::put('/leads/{id}/convert', [CrmController::class, 'convertLead'])->name('leads.convert');
    
    // Deals/Opportunities management
    Route::get('/deals', [CrmController::class, 'deals'])->name('deals');
    Route::get('/deals/create', [CrmController::class, 'createDeal'])->name('deals.create');
    Route::post('/deals', [CrmController::class, 'storeDeal'])->name('deals.store');
    Route::get('/deals/{id}', [CrmController::class, 'showDeal'])->name('deals.show');
    Route::get('/deals/{id}/edit', [CrmController::class, 'editDeal'])->name('deals.edit');
    Route::put('/deals/{id}', [CrmController::class, 'updateDeal'])->name('deals.update');
    Route::delete('/deals/{id}', [CrmController::class, 'deleteDeal'])->name('deals.delete');
    
    // Activities (Tasks, Calls, Meetings)
    Route::get('/activities', [CrmController::class, 'activities'])->name('activities');
    Route::get('/activities/create', [CrmController::class, 'createActivity'])->name('activities.create');
    Route::post('/activities', [CrmController::class, 'storeActivity'])->name('activities.store');
    Route::get('/activities/{id}', [CrmController::class, 'showActivity'])->name('activities.show');
    Route::get('/activities/{id}/edit', [CrmController::class, 'editActivity'])->name('activities.edit');
    Route::put('/activities/{id}', [CrmController::class, 'updateActivity'])->name('activities.update');
    Route::delete('/activities/{id}', [CrmController::class, 'deleteActivity'])->name('activities.delete');
    
    // Reports
    Route::get('/reports', [CrmController::class, 'reports'])->name('reports');
    Route::get('/reports/sales', [CrmController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/leads', [CrmController::class, 'leadsReport'])->name('reports.leads');
    Route::get('/reports/deals', [CrmController::class, 'dealsReport'])->name('reports.deals');
    
    // Settings
    Route::get('/settings', [CrmController::class, 'settings'])->name('settings');
    Route::post('/settings', [CrmController::class, 'updateSettings'])->name('settings.update');
    
    // API endpoints for AJAX operations
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/contacts', [CrmController::class, 'getContacts'])->name('contacts');
        Route::get('/leads', [CrmController::class, 'getLeads'])->name('leads');
        Route::get('/deals', [CrmController::class, 'getDeals'])->name('deals');
        Route::get('/activities', [CrmController::class, 'getActivities'])->name('activities');
        Route::post('/contact/quick-add', [CrmController::class, 'quickAddContact'])->name('contact.quick-add');
        Route::post('/lead/quick-add', [CrmController::class, 'quickAddLead'])->name('lead.quick-add');
        Route::post('/deal/quick-add', [CrmController::class, 'quickAddDeal'])->name('deal.quick-add');
        Route::post('/activity/quick-add', [CrmController::class, 'quickAddActivity'])->name('activity.quick-add');
    });
});
