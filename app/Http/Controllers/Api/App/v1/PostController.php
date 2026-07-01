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

}
