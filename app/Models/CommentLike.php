<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
  protected $table = 'comment_likes';
  protected $fillable = ['user_id', 'comment_id'];

  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  /*
  * Get the user that owns the comment like.
  */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /*
  * Get the comment that owns the comment like.
  */
  public function comment()
  {
    return $this->belongsTo(Comment::class);
  }
}
