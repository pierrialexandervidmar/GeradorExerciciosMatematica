<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'home'])->name('home');
Route::post('/generate-exercises', [MainController::class, 'generateExcercises'])->name('generateExcercises');
Route::get('/print-excercises', [MainController::class, 'printExcercises'])->name('printExcercises');
Route::get('/export-excercises', [MainController::class, 'exportExcercises'])->name('exportExcercises');

