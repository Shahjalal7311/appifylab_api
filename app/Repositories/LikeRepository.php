<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\Comment;
use App\Models\CommentLike;

class LikeRepository
{
  public function togglePostLike($user, Post $post): array
  {
    $liked = $this->toggle(PostLike::class, ['user_id' => $user->id, 'post_id' => $post->id]);
    return [
      'liked'       => $liked,
      'likes_count' => PostLike::where('post_id', $post->id)->count(),
    ];
  }

  public function toggleCommentLike($user, Comment $comment): array
  {
    $liked = $this->toggle(CommentLike::class, ['user_id' => $user->id, 'comment_id' => $comment->id]);
    return [
      'liked'       => $liked,
      'likes_count' => CommentLike::where('comment_id', $comment->id)->count(),
    ];
  }

  private function toggle(string $model, array $conditions): bool
  {
    $existing = $model::where($conditions)->first();
    if ($existing) {
      $existing->delete();
      return false;
    }
    $model::create($conditions);
    return true;
  }
}
