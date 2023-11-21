<?php

use App\Http\Controllers\cartController;
use App\Http\Controllers\categoryController;
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


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [userController::class, 'logout']);
    Route::get('/getowninfo', [userController::class, 'getOwnInfo']);

    Route::post('/cart/{product_id}', [cartController::class, 'add']);
    Route::delete('/cart/{id}', [cartController::class, 'delete']);
    Route::get('/cart', [cartController::class, 'show']);

    Route::post('/order', [orderController::class, 'order']);
    Route::get('/order', [orderController::class, 'show']);

    Route::post('/product', [productsController::class, 'add']);
    Route::delete('/product/{id}', [productsController::class, 'delete']);
    Route::post('/product/{id}', [productsController::class, 'update']);

    Route::post('/addcategory', [categoryController::class, 'addCategory']);
    Route::get('/getcategories', [categoryController::class, 'getCategory']);
    Route::post('/deletecategory', [categoryController::class, 'categoryDelete']);
});
