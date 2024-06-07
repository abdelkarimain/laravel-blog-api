<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\LikeController;
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


// routes for managing posts [private routes]
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
    Route::get('getallcomments', [CommentController::class, 'index']);
    Route::post('comment/store', [CommentController::class, 'storeComment']);
    Route::get('comments/user/{userId}', [CommentController::class, 'getUser']);
    Route::put('comment/editComment/{commentId}', [CommentController::class, 'editComment']);
    Route::delete('comment/deleteComment/{commentId}', [CommentController::class, 'deleteComment']);

    // Routes for managing likes
    Route::get('/like-status/{postId}', [LikeController::class, 'get_like_status']);
    Route::post('/like', [LikeController::class, 'save_like']);
});

// Routes for showing posts [public]
Route::get('post/findbyid/{id}', [PostController::class, 'showById']);
Route::get('post/{slug}', [PostController::class, 'show']);
Route::get('posts/recent/{postslug}', [PostController::class, 'recentposts']);
Route::get('posts/related/{slug}', [PostController::class, 'relatedPosts']);
Route::get('posts/all', [PostController::class, 'getAllPosts']);
Route::get('posts/allnopaginate', [PostController::class, 'allnopaginate']);
Route::get('posts/bycategory/{category}/{paginate?}', [PostController::class, 'getPostsByCategory']);
Route::get('posts/topcategories/{num}', [PostController::class, 'topcategories']);

// Routes for showing comments and likes [public]
Route::get('comments/show/{postId}', [CommentController::class, 'getPostComments']);
Route::get('/likes/{postId}', [LikeController::class, 'get_reaction_count']);
