<?php

use App\Http\Controllers\cartController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', [userController::class, 'register']);
Route::post('/login', [userController::class, 'login']);


Route::get('/products', [productsController::class, 'show']);
Route::get('/product', function () {
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [userController::class, 'logout']);

    Route::post('/cart/{product_id}', [cartController::class, 'add']);
    Route::delete('/cart/{id}', [cartController::class, 'delete']);
    Route::get('/cart', [cartController::class, 'show']);

    Route::post('/order', [orderController::class, 'order']);
    Route::get('/order', [orderController::class, 'show']);

    Route::post('/product', [productsController::class, 'add']);
    Route::delete('/product/{id}', [productsController::class, 'delete']);
    Route::patch('/product/{id}', [productsController::class, 'update']);
});
