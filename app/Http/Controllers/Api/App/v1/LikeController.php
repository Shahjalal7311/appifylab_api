<?php

namespace App\Http\Controllers\Api\App\v1;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Repositories\LikeRepository;

class LikeController extends ApiController
{
  public function __construct(private LikeRepository $likeRepository)
  {
    parent::__construct();
  }

  public function togglePostLike($postId)
  {
    try {
      $post = Post::findOrFail($postId);
      if ($post->visibility == 0 && $post->user_id !== $this->user->id) {
        return $this->errorResponse(['error' => 'This post is private.'], 403);
      }
      $result = $this->likeRepository->togglePostLike($this->user, $post);
      return $this->successResponse($result);
    } catch (\Exception $e) {
      return $this->errorResponse(['error' => 'Failed to toggle like.'], 500);
    }
  }

  public function postLikers($postId)
  {
    $post = Post::findOrFail($postId);
    if ($post->visibility == 0 && $post->user_id !== $this->user->id) {
      return $this->errorResponse(['error' => 'This post is private.'], 403);
    }
    $likers = PostLike::with('user')->where('post_id', $post->id)->get()
        ->map(fn($like) => [
            'id'         => $like->user->id,
            'first_name' => $like->user->first_name,
            'last_name'  => $like->user->last_name,
        ]);
    return $this->successResponse(['likers' => $likers]);
  }

  public function toggleCommentLike($commentId)
  {
    try {
      $comment = Comment::findOrFail($commentId);
      $post = $comment->post;
      if ($post->visibility == 0 && $post->user_id !== $this->user->id) {
        return $this->errorResponse(['error' => 'This post is private.'], 403);
      }
      $result = $this->likeRepository->toggleCommentLike($this->user, $comment);
      return $this->successResponse($result);
    } catch (\Exception $e) {
      return $this->errorResponse(['error' => 'Failed to toggle like.'], 500);
    }
  }

  public function commentLikers($commentId)
  {
    try{
      $comment = Comment::findOrFail($commentId);
      $post = $comment->post;
      if ($post->visibility == 0 && $post->user_id !== $this->user->id) {
        return $this->errorResponse(['error' => 'This post is private.'], 403);
      }
      $likers = CommentLike::with('user')->where('comment_id', $comment->id)->get()
          ->map(fn($like) => [
              'id'         => $like->user->id,
              'first_name' => $like->user->first_name,
              'last_name'  => $like->user->last_name,
          ]);
      return $this->successResponse(['likers' => $likers]);
    } catch (\Exception $e) {
      return $this->errorResponse(['error' => 'Failed to fetch likers.'], 500);
    }
  }
}
