<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BlogController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'dashboard'], function(){
        Route::group(['prefix' => 'blog'], function(){
            Route::get('/', [BlogController::class, 'index']);
            Route::get('/columns', [BlogController::class, 'getColumns']);
            Route::post('/store', [BlogController::class, 'store']);
            Route::get('/show/{blog}', [BlogController::class, 'show']);
            Route::post('/update/{blog}', [BlogController::class, 'update']);
            Route::delete('/delete/{blog}', [BlogController::class, 'delete']);
        });
    });
});
