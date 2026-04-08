<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonneeController;
use App\Http\Controllers\ExerciceController;
use App\Http\Controllers\ExerciceGestionController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\BiologieController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DonneeController::class, 'index'])->name('dashboard');
    Route::get('/activite', [ExerciceGestionController::class, 'activite'])->name('activite');
    Route::post('/activite', [SeanceController::class, 'store'])->name('activite.store');
    Route::get('/activite/{seance}/edit', [SeanceController::class, 'edit'])->name('activite.edit');
    Route::put('/activite/{seance}', [SeanceController::class, 'update'])->name('activite.update');
    Route::delete('/activite/{seance}', [SeanceController::class, 'destroy'])->name('activite.destroy');
    Route::get('/exercices', [ExerciceController::class, 'index'])->name('exercices.index');
    Route::get('/biologie', [BiologieController::class, 'index'])->name('biologie');
    Route::post('/biologie', [BiologieController::class, 'store'])->name('biologie.store');
    Route::put('/biologie/{analyse}', [BiologieController::class, 'update'])->name('biologie.update');
    Route::delete('/biologie/{analyse}', [BiologieController::class, 'destroy'])->name('biologie.destroy');
    Route::post('/donnees', [DonneeController::class, 'store'])->name('donnees.store');
    Route::delete('/donnees/{donnee}', [DonneeController::class, 'destroy'])->name('donnees.destroy');
    Route::post('/donnees/import', [DonneeController::class, 'importCsv'])->name('donnees.import.csv');

    // 📢 C'est cette ligne qui est critique
    Route::post('/donnees/{donnee}/update', [DonneeController::class, 'update'])->name('donnees.update');

    Route::get('/exercices/gestion', [ExerciceGestionController::class, 'index'])->name('exercices.gestion');
    Route::post('/exercices/categories', [ExerciceGestionController::class, 'storeCategory'])->name('exercices.categories.store');
    Route::post('/exercices/types', [ExerciceGestionController::class, 'storeType'])->name('exercices.types.store');
    Route::post('/exercices', [ExerciceGestionController::class, 'storeExercice'])->name('exercices.store');
    Route::delete('/exercices/{exercice}', [ExerciceGestionController::class, 'destroyExercice'])->name('exercices.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
