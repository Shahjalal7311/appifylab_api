<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
    ];
  }

  public function messages()
  {
    return [
      'first_name.required' => 'The first name is required.',
      'first_name.string' => 'The first name must be a string.',
      'last_name.required' => 'The last name is required.',
      'last_name.string' => 'The last name must be a string.',
      'email.required' => 'The email is required.',
      'email.string' => 'The email must be a string.',
      'email.email' => 'The email must be a valid email address.',
      'password.required' => 'The password is required.',
      'password.string' => 'The password must be a string.',
      'password.min' => 'The password must be at least 8 characters.',
      'password.confirmed' => 'The password confirmation does not match.',
    ];
  }

}