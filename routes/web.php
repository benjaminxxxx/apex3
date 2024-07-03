<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficosController;
use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\PostController;


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

    Route::get('/noticia/{slug}', [PostController::class,'noticia'])->name('noticia');
    Route::get('/evento/{slug}', function () {
        return view('post.new');
    })->name('evento');
    Route::get('/publicacion/{slug}', function () {
        return view('post.new');
    })->name('publicacion');
    Route::get('/foro/{slug}', function () {
        return view('post.new');
    })->name('foro');
    
    Route::get('/noticias', [NoticiasController::class,'index'])->name('notices');
    Route::get('/noticias/cargar-mas-noticias', [NoticiasController::class, 'loadMoreNotices'])->name('notices-load-more');
    
    Route::get('/post/nuevo/{type?}', [PostController::class,'index'])->name('post.new');

    Route::get('/contacto', function () {
        return view('admin.contacto');
    })->name('contact');

    Route::get('/graficos/{chart_id?}', [GraficosController::class,'index'])->name('charts');
    Route::delete('/graficos/eliminar/{chart_id}', [GraficosController::class,'destroy'])->name('chart.destroy');
    Route::post('/graficos/importar', [GraficosController::class,'import_document'])->name('charts.import');
    Route::post('/graficos/guardar', [GraficosController::class,'store'])->name('charts.store');
});
