<?php

use App\Http\Controllers\CuacaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/cuaca', [CuacaController::class, 'index']);
// Route::get('/cuaca', [CuacaController::class, 'index']);
Route::get('/api/cuaca', [CuacaController::class, 'getCuaca']);
