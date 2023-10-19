<?php

use Illuminate\Http\Request;
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

Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function () {
    Route::post('/', [App\Http\Controllers\CustomerController::class, 'create']);
    Route::get('/', [App\Http\Controllers\CustomerController::class, 'listAll']);
    Route::get('/filters', [App\Http\Controllers\CustomerController::class, 'findByFilters']);
    Route::get('/{id}', [App\Http\Controllers\CustomerController::class, 'findById']);
    Route::put('/{id}', [App\Http\Controllers\CustomerController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\CustomerController::class, 'delete']);
});

Route::group(['namespace' => 'Product', 'prefix' => 'product'], function () {
    Route::post('/', [App\Http\Controllers\ProductController::class, 'create']);
    Route::get('/', [App\Http\Controllers\ProductController::class, 'listAll']);
    Route::get('/filters', [App\Http\Controllers\ProductController::class, 'findByFilters']);
    Route::get('/{id}', [App\Http\Controllers\ProductController::class, 'findById']);
    Route::post('/{id}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\ProductController::class, 'delete']);
    Route::get('/image/{fileName}', [App\Http\Controllers\ProductController::class, 'viewImage']);
});

Route::group(['namespace' => 'Order', 'prefix' => 'order'], function () {
    Route::post('/', [App\Http\Controllers\OrderController::class, 'create']);
    Route::get('/customer/{id}', [App\Http\Controllers\OrderController::class, 'findByCustomerId']);
    Route::get('/{id}', [App\Http\Controllers\OrderController::class, 'findById']);
    Route::put('/{id}', [App\Http\Controllers\OrderController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\OrderController::class, 'delete']);
});