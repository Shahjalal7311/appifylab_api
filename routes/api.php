<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\App\v1\AuthController;
use App\Http\Controllers\Api\App\v1\PostController;
use App\Http\Controllers\Api\App\v1\CommentController;
use App\Http\Controllers\Api\App\v1\LikeController;

Route::prefix('app/v1')->group(function () {
  Route::post('login', [AuthController::class, 'login']);
  Route::post('register', [AuthController::class, 'register']);

  Route::middleware(['jwt.auth'])->group(function () {

    Route::prefix('posts')->group(function() {
      Route::get('/', [PostController::class, 'index']);
      Route::post('/', [PostController::class, 'store']);
      Route::get('/{postId}/likers', [LikeController::class, 'postLikers']);
      Route::get('/{postId}/comments', [CommentController::class, 'index']);
      Route::post('/{postId}/like', [LikeController::class, 'togglePostLike']);
      Route::post('/{postId}/comments', [CommentController::class, 'store']);
    });

    Route::prefix('comments')->group(function() {
      Route::get('/{commentId}/likers', [LikeController::class, 'commentLikers']);
      Route::post('/{commentId}/replies', [CommentController::class, 'reply']);
      Route::post('/{commentId}/like', [LikeController::class, 'toggleCommentLike']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
  });
});
