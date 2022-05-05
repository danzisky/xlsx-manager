<?php

use App\Http\Controllers\FilesController;
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

Route::resource('sheets', FilesController::class)->middleware('auth');

Route::get('/', function () {
    return view('welcome');});



Route::get('/documentation', function () {
    return view('documentation');
})->middleware(['auth'])->name('documentation');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
