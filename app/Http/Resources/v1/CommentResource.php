<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
        'id'          => $this->id,
        'body'        => $this->body,
        'is_reply'    => !is_null($this->parent_id),
        'parent_id'   => $this->parent_id,
        'author'      => [
          'id'         => $this->user->id,
          'first_name' => $this->user->first_name,
          'last_name'  => $this->user->last_name,
        ],
        'likes_count' => $this->likes->count(),
        'is_liked'    => $this->likes->contains('user_id', $this->user_id),
        'replies'     => CommentResource::collection($this->whenLoaded('replies')),
        'created_at'  => $this->created_at->toIso8601String(),
    ];
  }
}
