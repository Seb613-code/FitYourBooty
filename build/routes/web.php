<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonneeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('donnees', DonneeController::class);
    Route::post('/donnees/import', [DonneeController::class, 'importCsv'])->name('donnees.import.csv');
});

require __DIR__.'/auth.php';
