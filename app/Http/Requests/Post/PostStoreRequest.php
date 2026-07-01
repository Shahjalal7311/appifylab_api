<?php 
namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'content'    => 'required|string|max:5000',
      'image'      => 'nullable|image|max:5120',
      'visibility' => 'required|in:1,0', // 1 for public, 0 for private
    ];
  }

  public function messages()
  {
    return [
      'content.required' => 'The content is required.',
      'content.string' => 'The content must be a string.',
      'content.max' => 'The content may not be greater than 5000 characters.',
      'image.image' => 'The image must be a valid image file.',
      'image.max' => 'The image may not be greater than 5120 kilobytes.',
      'visibility.required' => 'The visibility is required.',
      'visibility.in' => 'The visibility must be either public (1) or private (0).',
    ];
  }

}