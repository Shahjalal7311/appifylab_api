<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;
  
  protected $table = 'posts';
  protected $fillable = ['user_id', 'content', 'visibility'];

  protected $casts = [
    'visibility'  => 'boolean',
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];
  
  /**
   * Register media collections
   *
   * @return void
   */
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('post_thumbnail')
        ->singleFile()
        ->useDisk(config('media-library.disk_name'));
  }

  /**
   * Register media conversions
   *
   * @param Media|null $media
   * @return void
   */
  public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('thumb')
        ->width(150)
        ->height(150)
        ->sharpen(10)
        ->performOnCollections('post_thumbnail');

    $this->addMediaConversion('preview')
        ->width(300)
        ->height(300)
        ->performOnCollections('post_thumbnail');
  }

  /**
   * Get the profile photo URL
   *
   * @return string|null
   */
  public function getProfilePhotoUrlAttribute(): ?string
  {
    return $this->post_thumbnail('post_thumbnail', 'preview');
  }

  /**
   * Get the profile photo thumbnail URL
   *
   * @return string|null
   */
  public function getProfilePhotoThumbnailUrlAttribute(): ?string
  {
    return $this->post_thumbnail('post_thumbnail', 'thumb');
  }

  /**
   * The "booting" method of the model.
   *
   * @return void
   */
  protected static function boot()
  {
    parent::boot();
    static::deleting(function ($post) {
      $post->media()->delete();
    });
  }

  /*
  * Get the user that owns the post.
  */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /*
  * Get the likes for the post.
  */
  public function likes()
  {
    return $this->hasMany(PostLike::class);
  }

  /*
  * Get the comments for the post.
  */
  public function comments()
  {
    return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
  }
  
}
