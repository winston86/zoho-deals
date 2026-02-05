<?php

use Illuminate\Support\Facades\Route;
use Winston86\ZohoDeals\Http\Controllers\ZohoApiController;

Route::get('/tickets', [ZohoApiController::class, 'getTickets'])->name('zoho.tickets.index');
Route::get('/tickets/{id}', [ZohoApiController::class, 'showTicket'])->name('zoho.tickets.show');

Route::get('/accounts', [ZohoApiController::class, 'getAccounts']);
Route::post('/add-account', [ZohoApiController::class, 'addAccount']);
Route::get('/search-accounts', [ZohoApiController::class, 'searchAccounts']);

Route::get('/deals', [ZohoApiController::class, 'getDeals']);
Route::post('/add-deal', [ZohoApiController::class, 'addDeal']);
Route::post('/store-deal', [ZohoApiController::class, 'storeDeal']);