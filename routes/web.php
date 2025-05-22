<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AnalyticController;

Route::get('/', action: [ClientController::class, 'index']);
Route::post('clients/search', [ClientController::class, 'search'])->name('clients.search');
Route::get('clientsss', [ClientController::class, 'index'])->name('clients.index');
Route::get(uri: 'clients/edit/{id}', action: [ClientController::class, 'edit'])->name(name: 'clients.edit');
Route::post(uri: 'clients.store', action: [ClientController::class, 'store'])->name(name: 'clients.store');
// Route::post(uri: 'clients.update/{id}', action: [ClientController::class, 'update'])->name(name: 'clients.update');
Route::put('clients/update/{id}', [ClientController::class, 'update'])->name('clients.update');
Route::delete('client.destroy/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
Route::get('clients/export', [ClientController::class, 'export'])->name('clients.export');
Route::get('/filters', [ClientController::class, 'filter'])->name('filter');
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/filter-analytic', [ClientController::class, 'filterAnalytic'])->name('filter.analytic');
Route::get('/filter-analytic', [AnalyticController::class, 'filter'])->name('filter.analytic');




