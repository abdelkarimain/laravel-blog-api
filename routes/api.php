<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    // Routes for managing Auth [public routes]
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('google', [AuthController::class, 'google']);

    // Routes for managing authenticated user
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('update', [AuthController::class, 'update']);
        Route::post('delete', [AuthController::class, 'destroy']);

    });
});



Route::middleware('auth:sanctum')->group(function () {
    // Routes for managing posts
    Route::get('posts', [PostController::class, 'index']);
    Route::post('posts', [PostController::class, 'store']);
    Route::post('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);

    // routes for managing users
    Route::get('users', [UserController::class, 'index']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Routes for managing commnets
    Route::post('comment/store', [CommentController::class, 'storeComment']);
    Route::get('comments/show/{postId}', [CommentController::class, 'getPostComments']);
    Route::get('comments/user/{userId}', [CommentController::class, 'getUser']);
    Route::put('comment/editcomment/{commentId}', [CommentController::class, 'editComment']);
    Route::delete('comment/deleteComment/{commentId}', [CommentController::class, 'deleteComment']);

});

// Routes for showing posts [public]
Route::get('post/findbyid/{id}', [PostController::class, 'showById']);
Route::get('post/{slug}', [PostController::class, 'show']);
Route::get('posts/recent', [PostController::class, 'recentposts']);
Route::get('posts/related/{slug}', [PostController::class, 'relatedPosts']);


// Routes for showing commnets
