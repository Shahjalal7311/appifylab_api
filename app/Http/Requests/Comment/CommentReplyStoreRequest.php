<?php 
namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentReplyStoreRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'body'    => 'required|string|max:255',
    ];
  }

  public function messages()
  {
    return [
      'body.required' => 'The body is required.',
      'body.string' => 'The body must be a string.',
      'body.max' => 'The body may not be greater than 255 characters.',
    ];
  }

}