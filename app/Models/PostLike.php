<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
  protected $table = 'post_likes';
  protected $fillable = ['user_id', 'post_id'];

  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];
  /*
  * Get the user that owns the post like.
  */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /*
  * Get the post that owns the post like.
  */
  public function post()
  {
    return $this->belongsTo(Post::class);
  }
}
