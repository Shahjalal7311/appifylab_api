<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Comment;

class CommentRepository
{
  public function store($user, Post $post, string $body): Comment
  {
    return Comment::create([
      'user_id' => $user->id,
      'post_id' => $post->id,
      'body'    => $body,
    ]);
  }

  public function reply($user, Comment $comment, string $body): Comment
  {
    return Comment::create([
      'user_id'   => $user->id,
      'post_id'   => $comment->post_id,
      'parent_id' => $comment->id,
      'body'      => $body,
    ]);
  }
}
