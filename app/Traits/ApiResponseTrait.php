<?php

namespace App\Traits;

trait ApiResponseTrait
{
  /**
   * JSON response for successful operations
   */
  protected function successResponse($data = [], $code = 200)
  {
    return response()->json([
      'success' => true,
      'data' => $data
    ]);
  }

  /**
   *  JSON response for errors
   */
  protected function errorResponse($errors = [], $code = 400)
  {
    return response()->json([
      'success' => false,
      'errors' => $errors
    ], $code);
  }
}