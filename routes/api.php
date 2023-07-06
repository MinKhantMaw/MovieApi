<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\CommentController;


Route::controller(AuthenticationController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::get('fetchAll', [MovieController::class, 'index']);
Route::get('relatedMovie/{id}', [MovieController::class, 'getRelatedMovies']);

Route::middleware(['auth:api'])->group(function () {
    Route::controller(MovieController::class)->prefix('movies')->group(function () {
        // Route::get('fetchAll',  'index');
        Route::post('create',  'create');
        Route::get('/details/{id}',  'details');
        Route::post('update/{id}',  'update');
        Route::delete('delete/{id}',  'delete');
    });

    Route::controller(CommentController::class)->prefix('comments')->group(function () {
        Route::post('create', 'create');
    });
});
