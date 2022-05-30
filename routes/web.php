<?php

use App\Http\Controllers\MapaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/mapa', [MapaController::class, 'mapa'])->name('mapa.index');
    Route::post('/mapa/novo', [MapaController::class,'novo'])->name('mapa.novo');
    Route::post('/mapa/add', [MapaController::class,'add'])->name('mapa.add');
    Route::post('/mapa/delete', [MapaController::class,'delete'])->name('mapa.delete');
    Route::post('/mapa', [MapaController::class,'save'])->name('mapa.save');
});

Route::get('/', function () {
    return view('welcome');
});


require __DIR__.'/auth.php';
