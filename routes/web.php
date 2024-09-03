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
use Illuminate\Support\Facades\Artisan;


Route::get('/update-composer', function () {
    // Ejecutar el comando de Composer update
    $output = shell_exec('composer update 2>&1');
    return "<pre>$output</pre>";
});
Route::get('/artisan-config-cache', function () {
    Artisan::call('config:cache');
    return "Caché de configuración regenerada";
});

Route::get('/artisan-config-clear', function () {
    Artisan::call('config:clear');
    return "Caché de configuración limpiada";
});
Route::get('/artisan-optimize-clear', function () {
    Artisan::call('optimize:clear');
    return "Optimizaciones limpiadas";
});
Route::get('/remove-livewire-alert', function () {
    exec('composer remove jantinnerezo/livewire-alert');
    return "Paquete eliminado.";
});
Route::get('/install-livewire-alert', function () {
    exec('composer require jantinnerezo/livewire-alert');
    return "Paquete instalado.";
});

Route::get('/run-composer-update', function () {
    $output = null;
    $returnVar = null;
    exec('cd /home/allypexa/public_html && composer update', $output, $returnVar);

    if ($returnVar === 0) {
        return "Composer update ejecutado exitosamente.";
    } else {
        return "Error ejecutando composer update: " . implode("\n", $output);
    }
});

Route::get('/artisan-optimize', function () {
    Artisan::call('optimize:clear'); // Limpia el caché de la aplicación
    Artisan::call('config:clear'); // Limpia la caché de configuración
    Artisan::call('route:clear'); // Limpia la caché de rutas
    Artisan::call('view:clear'); // Limpia la caché de vistas
    return "Optimización y cachés limpiados";
});

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

    /*
    Route::get('/publicacion/{slug}', function () {
        return view('post.new');
    })->name('publicacion');
    Route::get('/foro/{slug}', function () {
        return view('post.new');
    })->name('foro');*/
    
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
