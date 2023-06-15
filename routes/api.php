<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Client\BlogController as ClientBlogController;
use App\Http\Controllers\Admin\FileUploadController;

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

Route::group(['prefix' => 'blog'], function(){
    Route::get('/', [ClientBlogController::class, 'index']);
});

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/file-upload', FileUploadController::class);

    Route::group(['prefix' => 'dashboard'], function(){
        Route::group(['prefix' => 'blog'], function(){
            Route::get('/', [BlogController::class, 'index']);
            Route::get('/columns', [BlogController::class, 'getColumns']);
            Route::post('/store', [BlogController::class, 'store']);
            Route::get('/show/{blog}', [BlogController::class, 'show']);
            Route::put('/update/{blog}', [BlogController::class, 'update']);
            Route::delete('/delete/{blog}', [BlogController::class, 'delete']);
            Route::delete('/delete-image/{blog}', [BlogController::class, 'deleteImage']);
            Route::delete('/delete-gallery-image/{blog}', [BlogController::class, 'deleteGalleryImage']);

            Route::group(['prefix' => 'category'], function(){
                Route::get('/', [BlogCategoryController::class, 'index']);
                Route::get('/columns', [BlogCategoryController::class, 'getColumns']);
                Route::post('/store', [BlogCategoryController::class, 'store']);
                Route::get('/show/{category}', [BlogCategoryController::class, 'show']);
                Route::put('/update/{category}', [BlogCategoryController::class, 'update']);
                Route::delete('/delete/{category}', [BlogCategoryController::class, 'delete']);
            });
        });

        Route::group(['prefix' => 'menu'], function(){
            Route::get('/', [MenuController::class, 'index']);
            Route::get('/columns', [MenuController::class, 'getColumns']);
            Route::get('/categories', [MenuController::class, 'getCategories']);
            Route::post('/store', [MenuController::class, 'store']);
            Route::get('/show/{menu}', [MenuController::class, 'show']);
            Route::post('/update/{menu}', [MenuController::class, 'update']);
            Route::delete('/delete/{menu}', [MenuController::class, 'delete']);
        });
    });
});
