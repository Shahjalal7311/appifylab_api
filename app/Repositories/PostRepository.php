<?php

namespace App\Repositories;

use App\Models\Post;
use App\Traits\MediaUploadTrait;
use App\Http\Requests\Post\PostStoreRequest;

class PostRepository
{
  use MediaUploadTrait;

  public function store($user, PostStoreRequest $request): Post
  {
    try{
      $post = Post::create([
        'user_id'    => $user->id,
        'content'    => $request->content,
        'visibility' => $request->visibility,
      ]);
      if ($request->hasFile('image')) {
        $this->upload($post, $request->file('image'), 'post-' . $post->id, 'post_thumbnail');
      }
      return $post;
    } catch (\Exception $e) {
      throw new \Exception('Failed to create post: ' . $e->getMessage());
    }
  }

  public function toggleVisibility(Post $post): Post
  {
    $post->visibility = ! $post->visibility;
    $post->save();
    return $post;
  }
}
