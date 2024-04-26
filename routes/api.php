<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
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



Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('google', [AuthController::class, 'google']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('update', [AuthController::class, 'update']);
        Route::post('delete', [AuthController::class, 'destroy']);

    });
});

// Routes for managing posts
Route::get('posts', [PostController::class, 'index'])->middleware('auth:sanctum');
Route::post('posts', [PostController::class, 'store'])->middleware('auth:sanctum');
Route::get('post/{slug}', [PostController::class, 'show'])->middleware('auth:sanctum');
Route::post('posts/{id}', [PostController::class, 'update'])->middleware('auth:sanctum');
Route::delete('posts/{id}', [PostController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('posts/recent', [PostController::class, 'recentposts'])->middleware('auth:sanctum');


// routes for managing users
Route::get('users', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::delete('users/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum');
