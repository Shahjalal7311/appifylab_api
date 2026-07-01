<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id'           => $this->id,
      'content'      => $this->content,
      'thumbnail' => $this->getFirstMedia('post_thumbnail') ? [
        'url'           => $this->getFirstMedia('post_thumbnail')->getUrl(),
        'thumbnail_url' => $this->getFirstMedia('post_thumbnail')->getUrl(),
      ] : null,
      'visibility'   => $this->visibility,
      'is_owner'     => $this->user_id === auth()->id(),
      'user'       => [
        'avatar'     => null,
        'full_name'  => $this->user->first_name . ' ' . $this->user->last_name,
        'first_name' => $this->user->first_name,
        'last_name'  => $this->user->last_name,
      ],
      'likes_count'  => $this->likes->count(),
      'is_liked'     => $this->likes->contains('user_id', auth()->id()),
      'created_at'   => $this->created_at->toIso8601String(),
    ];
  }
}
