<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'email' => 'required|string|email|max:255',
      'password' => 'required|string|min:8',
    ];
  }

  public function messages()
  {
    return [
      'email.required' => 'The email is required.',
      'email.email' => 'The email must be a valid email address.',
      'email.max' => 'The email may not be greater than 255 characters.',
      'password.required' => 'The password is required.',
      'password.min' => 'The password must be at least 8 characters.',
    ];
  }

}