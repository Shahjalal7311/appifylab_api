<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Exception;

trait MediaUploadTrait
{ 
  /**
   * Upload a file to the media collection
  */
  public function upload($model, $file, $name, $collectionName, $index = null)
  {
    $this->validateFile($file, $index);
    return $model->addMedia($file)
        ->usingFileName($this->generateFileName($name, $file, $collectionName))
        ->toMediaCollection($collectionName);
  }
  /**
   * Generate a consistent filename
  */
  protected function generateFileName($name, $file, $collectionName)
  {
    return Str::slug($name) . "-{$collectionName}-" . uniqid() . '.' . $file->extension();
  }
  /*
  * Validate the uploaded file
  */
  protected function validateFile($file, $index = null)
  {
    if (!$file->isValid()) {
      if ($index !== null) {
        throw new Exception("Invalid file upload at index $index");
      }
      throw new Exception("Invalid file upload");
    }
  }
  /**
  * Upload attachments for a model
  */
  public function clearExistingAttachments($model, $collectionName = 'profile_photos')
  {
    if ($model->hasMedia($collectionName)) {
      $model->clearMediaCollection($collectionName);
    }
  }

}