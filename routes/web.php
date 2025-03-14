<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');

});
Route::get('/export-data', [ExportController::class, 'exportToExcel'])
    ->name('export.data');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

