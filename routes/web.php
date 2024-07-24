<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficosController;
use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\NewController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DocumentController;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Controllers\EventController;


Route::middleware([
    'auth:sanctum',
    CheckUserStatus::class,
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/usuarios', function () {
        return view('admin.usuarios');
    })->name('users');

    Route::get('/gestores', function () {
        return view('admin.managers');
    })->name('managers');

    Route::get('/socios', function () {
        return view('admin.partners');
    })->name('partners');

    Route::get('/documentos', [DocumentController::class,'index'])->name('documents');
    
    Route::get('/proyectos', [ProjectController::class,'index'])->name('projects');
    Route::get('/proyecto/{slug}', [ProjectController::class,'go'])->name('project');
    Route::get('/grupo/{slug}', [GroupController::class,'go'])->name('group.go');

    
    Route::get('/publicacion/{slug}', function () {
        return view('post.new');
    })->name('publicacion');
    Route::get('/foro/{slug}', function () {
        return view('post.new');
    })->name('foro');
    
    Route::get('/eventos', [EventController::class,'index'])->name('events');
    Route::get('/evento/{slug}', [EventController::class,'show'])->name('event');

    Route::get('/noticias', [NewController::class,'index'])->name('news');
    //Route::get('/noticias/cargar-mas-noticias', [NoticiasController::class, 'loadMoreNotices'])->name('news-load-more');
    Route::get('/noticia/{slug}', [NewController::class,'show'])->name('news.show');
    
    Route::get('/post/nuevo/{type?}', [PostController::class,'index'])->name('post.new');

    Route::get('/contacto', function () {
        return view('admin.contacto');
    })->name('contact');

    Route::get('/graficos/{chart_id?}', [GraficosController::class,'index'])->name('charts');
    Route::delete('/graficos/eliminar/{chart_id}', [GraficosController::class,'destroy'])->name('chart.destroy');
    Route::post('/graficos/importar', [GraficosController::class,'import_document'])->name('charts.import');
    Route::post('/graficos/guardar', [GraficosController::class,'store'])->name('charts.store');
});
