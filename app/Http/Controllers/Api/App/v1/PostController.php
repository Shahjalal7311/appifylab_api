<?php

namespace App\Http\Controllers\Api\App\v1;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\v1\PostResource;
use App\Http\Requests\Post\PostStoreRequest;
use App\Repositories\PostRepository;

class PostController extends ApiController
{
  public function __construct(private PostRepository $postRepository)
  {
    parent::__construct();
  }

  /*
  * Display a listing of the posts.
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    try {
      $authUserId = $this->user->id;
      $query = Post::with(['user', 'likes', 'media'])
          ->where(function ($q) use ($authUserId) {
              $q->where('visibility', 1)
                ->orWhere('user_id', $authUserId);
          })
          ->latest();
      $posts = $query->get();
      $posts = PostResource::collection($posts);
      return $this->successResponse($posts, 200);
    } catch (\Exception $e) {
      return $this->errorResponse('Failed to fetch posts.', 500);
    }
  }

  /**
   * Store a newly created post in storage.
   *
   * @param  \App\Http\Requests\Post\PostStoreRequest  $request
   * @return \Illuminate\Http\Response
  */
  public function store(PostStoreRequest $request)
  {
    try {
      $post = $this->postRepository->store($this->user, $request);
      $post->load(['user', 'likes', 'media']);

      return $this->successResponse([
        'message' => 'Post created successfully.',
        'post'    => new PostResource($post),
      ], 201);
    } catch (\Exception $e) {
      return $this->errorResponse(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Toggle a post's visibility between public and private.
   * Only the post's owner may change it.
   */
  public function toggleVisibility($postId)
  {
    try {
      $post = Post::findOrFail($postId);
      if ($post->user_id !== $this->user->id) {
        return $this->errorResponse(['error' => 'You can only change the visibility of your own posts.'], 403);
      }
      $post = $this->postRepository->toggleVisibility($post);
      $post->load(['user', 'likes', 'media']);

      return $this->successResponse([
        'message' => 'Post visibility updated.',
        'post'    => new PostResource($post),
      ]);
    } catch (\Exception $e) {
      return $this->errorResponse(['error' => 'Failed to update post visibility.'], 500);
    }
  }

}
