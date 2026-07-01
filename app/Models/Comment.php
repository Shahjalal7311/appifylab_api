<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
  use HasFactory;
  protected $table = 'comments';
  protected $fillable = ['user_id', 'post_id', 'parent_id', 'body'];

  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  /*
  * Get the user that owns the comment.
  */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  /*
  * Get the post that owns the comment.
  */
  public function post()
  {
    return $this->belongsTo(Post::class);
  }

  /*
  * Get the replies for the comment.
  */
  public function replies()
  {
    return $this->hasMany(Comment::class, 'parent_id')->latest();
  }

  /*
  * Get the likes for the comment.
  */
  public function likes()
  {
    return $this->hasMany(CommentLike::class);
  }
}
