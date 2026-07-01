<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
  public function map()
  {
    $this->mapApiRoutes();
  }

  protected function mapApiRoutes()
  {
    // General API Routes
    Route::middleware('api')
        ->prefix('api')
        ->namespace($this->namespace ?? null)
        ->group(base_path('routes/api.php'));
  }
}