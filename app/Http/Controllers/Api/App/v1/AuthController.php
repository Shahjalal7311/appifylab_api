<?php 
  namespace App\Http\Controllers\Api\App\v1;

  use App\Http\Controllers\Api\App\v1\ApiController;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Facades\DB;
  use Tymon\JWTAuth\Facades\JWTAuth;

  use App\Models\User;

  use App\Http\Resources\v1\LoginUserResources;

  use App\Http\Requests\Auth\AuthRegisterRequest;
  use App\Http\Requests\Auth\LoginRequest;

  class AuthController extends ApiController
  {
    public function __construct()
    {
      parent::__construct();
      // You can initialize any dependencies or services here if needed
    }

    /**
     * Login user with email and password
     */
    public function login(LoginRequest $request)
    {
      DB::beginTransaction();
      try {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
          return $this->errorResponse(['error' => 'Invalid credentials.'], 401);
        }
        $user = auth()->user();
        DB::commit();
        return $this->successResponse([
          'message' => 'Login successful.',
          'access_token' => $token,
          'token_type' => 'Bearer',
          'user' => new LoginUserResources($user),
        ], 200);
      } catch (\Exception $e) {
        DB::rollBack();
        return $this->errorResponse(['error' => $e->getMessage()], 500);
      }
    }

    /**
     * Register a new user
     */
    public function register(AuthRegisterRequest $request)
    {
      DB::beginTransaction();
      try {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if ($user) {
          return $this->errorResponse(['error' => 'User already exists.'], 409);
        }
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        DB::commit();
        return $this->successResponse([
          'message' => 'User registered successfully.',
        ], 200);
      } catch (\Exception $e) {
        DB::rollBack();
        return $this->errorResponse(['error' => $e->getMessage()], 500);
      }
    }

    public function logout(Request $request)
    {
      try {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->successResponse(['message' => 'Logged out successfully.'], 200);
      } catch (\Exception $e) {
        return $this->errorResponse(['error' => $e->getMessage()], 500);
      }
    }

  }