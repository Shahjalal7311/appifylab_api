<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
      api: [
        __DIR__.'/../routes/api.php',
        __DIR__.'/../routes/web.php',
      ],
      commands: __DIR__.'/../routes/console.php',
      health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
      // Add CORS middleware for API routes
      $middleware->api(prepend: [
        \Illuminate\Http\Middleware\HandleCors::class,
      ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    // Force JSON response for API requests
    $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
      return $request->is('api/*') || $request->expectsJson();
    });

    // Custom error handling
    $exceptions->renderable(function (Throwable $exception, Request $request) {
    // Check if the request expects JSON
    
    if ($request->is('api/*') || $request->expectsJson()) {
      // Handle JWT Token Errors
      if ($exception instanceof TokenExpiredException) {
        return response()->json(['error' => 'Token has expired', 'status' => 419], 419);
      } elseif ($exception instanceof TokenInvalidException) {
        return response()->json(['error' => 'Invalid token', 'status' => 401], 401);
      } elseif ($exception instanceof JWTException) {
        return response()->json(['error' => 'Token is required', 'status' => 401], 401);
      }

      // Handle Authentication Errors
      if ($exception instanceof AuthenticationException) {
        return response()->json(['error' => 'Unauthorized', 'status' => 401], 401);
      }

      // Handle Authorization Errors (Gate/Policy failures)
      if ($exception instanceof AuthorizationException) {
        return response()->json([
            'error' => 'Forbidden',
            'message' => 'You do not have permission to perform this action',
            'status' => 403
        ], 403);
      }

      if ($exception instanceof AuthenticationException) {
        return response()->json([
          'error' => 'Unauthorized',
          'message' => 'Your login expire, please login again. ',
          'status' => 401
        ], 401);
      }

      // Handle Not Found Errors
      if ($exception instanceof NotFoundHttpException) {
        return response()->json(['error' => 'Resource not found', 'status' => 404], 404);
      }

      // Handle Validation Errors
      if ($exception instanceof ValidationException) {
        return response()->json([
          'error' => 'Validation failed',
          'messages' => $exception->errors(),
          'status' => 422
        ], 422);
      }
      
      // Default Error Response
      return response()->json([
          'error' => 'Something went wrong',
          'message' => $exception->getMessage(),
          'status' => 500
      ], 500);
    }
  });
})->create();
