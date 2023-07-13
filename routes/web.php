<?php

use App\Http\Controllers\GamesController;
use App\Http\Controllers\MovieController;
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

Route::get('/', [GamesController::class,'index'])->name('games.index');
Route::get('games/{slug}', [GamesController::class,'show'])->name('games.show');
Route::get('movie', [MovieController::class,'index'])->name('movie.index');
Route::get('movie/{slug}', [MovieController::class,'show'])->name('movie.show');
