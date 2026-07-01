<?php 

  namespace App\Http\Controllers\Api\App\v1;
  use App\Http\Controllers\Controller;
  use App\Traits\ApiResponseTrait;

  class ApiController extends Controller
  {
    use ApiResponseTrait;

    protected $user;

    public function __construct()
    {
      $this->user = auth()->user();
    }
  }