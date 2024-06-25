<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficosController;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/usuarios', function () {
        return view('admin.usuarios');
    })->name('usuarios');

    Route::get('/graficos', [GraficosController::class,'index'])->name('charts');
    Route::post('/graficos/importar', [GraficosController::class,'import_document'])->name('charts.import');
    Route::post('/graficos/guardar', [GraficosController::class,'store'])->name('charts.store');
});
