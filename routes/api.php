<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;

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
Route::post('login',  [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('logout',  [UserController::class, 'logout']);
    Route::get('athenticated', function () {
        return true;
    });
    Route::get('/user', [UserController::class, 'index']);
    Route::group(['prefix' => 'category'], function(){
        Route::get('index',  [CategoryController::class, 'index']);
        Route::post('create',  [CategoryController::class, 'create']);
        Route::post('edit',  [CategoryController::class, 'edit']);
        Route::get('show/{id}',  [CategoryController::class, 'show']);
        Route::get('delete/{id}',  [CategoryController::class, 'destroy']);
        Route::post('paginate',  [CategoryController::class, 'store']);

        Route::group(['prefix' => 'product'], function() {
            // Route::get('index', [ProductController::class, 'index']);
            Route::post('create', [ProductController::class, 'create']);
            Route::get('delete/{id}', [ProductController::class, 'destroy']);
            Route::post('edit', [ProductController::class, 'edit']);
            Route::post('paginate',  [ProductController::class, 'store']);
            Route::get('show/{id}',  [ProductController::class, 'show']);
        });
    });

    Route::group(['prefix' => 'color'], function() {
        Route::get('index', [ColorController::class, 'index']);
        Route::post('create', [ColorController::class, 'create']);
        Route::post('edit', [ColorController::class, 'edit']);
        Route::get('delete/{id}', [ColorController::class, 'delete']);
    });

    Route::group(['prefix' => 'size'], function() {
        Route::get('index', [SizeController::class, 'index']);
        Route::post('create', [SizeController::class, 'create']);
        Route::post('edit', [SizeController::class, 'edit']);
        Route::get('delete/{id}', [SizeController::class, 'delete']);
    });
});

