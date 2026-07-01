<?php

namespace App\Http\Controllers\Api\App\v1;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\v1\CommentResource;

use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentReplyStoreRequest;

use App\Repositories\CommentRepository;

class CommentController extends ApiController
{
  public function __construct(private CommentRepository $commentRepository)
  {
    parent::__construct();
  }

  public function index($postId)
  {
    try {
      $authUserId = $this->user->id;
      $post = Post::findOrFail($postId);
      if ($post->visibility == 0 && $post->user_id !== $authUserId) {
        return $this->errorResponse(['error' => 'This post is private.'], 403);
      }
      $comments = $post->comments()
          ->with(['user', 'likes', 'replies.user', 'replies.likes'])
          ->get();
      return $this->successResponse(CommentResource::collection($comments), 200);
    } catch (\Exception $e) {
      return $this->errorResponse('Failed to fetch comments.', 500);
    }
  }

  public function store(CommentStoreRequest $request, $postId)
  {
    try {
      $post = Post::findOrFail($postId);
      if ($post->visibility == 0 && $post->user_id !== $this->user->id) {
        return $this->errorResponse(['error' => 'This post is private.'], 403);
      }
      $comment = $this->commentRepository->store($this->user, $post, $request->body);
      $comment->load(['user', 'likes']);
      return $this->successResponse([
        'message' => 'Comment added.',
        'comment' => new CommentResource($comment),
      ], 201);
    } catch (\Exception $e) {
      return $this->errorResponse(['error' => $e->getMessage()], 500);
    }
  }

  public function reply(CommentReplyStoreRequest $request, $commentId)
  {
    try {
      $comment = Comment::findOrFail($commentId);
      if ($comment->post->visibility == 0 && $comment->post->user_id !== $this->user->id) {
        return $this->errorResponse(['error' => 'This post is private.'], 403);
      }
      if (!is_null($comment->parent_id)) {
        return $this->errorResponse(['error' => 'Cannot reply to a reply.'], 422);
      }
      $reply = $this->commentRepository->reply($this->user, $comment, $request->body);
      $reply->load(['user', 'likes']);
      return $this->successResponse([
        'message' => 'Reply added.',
        'reply'   => new CommentResource($reply),
      ], 201);
    } catch (\Exception $e) {
      return $this->errorResponse(['error' => $e->getMessage()], 500);
    }
  }
}
