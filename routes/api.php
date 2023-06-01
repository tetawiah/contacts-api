<?php

use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth')->group(function () {
//     Route::resource('/contact',ContactController::class);
// });

Route::resource('/contact',ContactController::class);
Route::post('/contact-upload',[FileController::class,'uploadCSV']);
Route::post('/contact-save',[ContactController::class,'storeRecords']);
