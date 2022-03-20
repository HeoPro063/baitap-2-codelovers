<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
});
Route::middleware('auth:sanctum')->get('/athenticated', function () {
    return true;
});
Route::post('login',  [UserController::class, 'login']);
Route::get('logout',  [UserController::class, 'logout']);

Route::group(['prefix' => 'category'], function(){
    Route::get('index',  [CategoryController::class, 'index']);
    Route::post('create',  [CategoryController::class, 'create']);
    Route::post('edit',  [CategoryController::class, 'edit']);
    Route::get('show/{id}',  [CategoryController::class, 'show']);
    Route::get('delete/{id}',  [CategoryController::class, 'destroy']);
    Route::post('paginate',  [CategoryController::class, 'store']);

    Route::group(['prefix' => 'product'], function() {
        Route::get('index', [ProductController::class, 'index']);
        Route::post('create', [ProductController::class, 'create']);
        Route::get('delete/{id}', [ProductController::class, 'destroy']);
        Route::post('edit', [ProductController::class, 'edit']);
        Route::post('paginate',  [ProductController::class, 'store']);
    });
});
