<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {      
      // Load migrations from multiple directories
      $mainPath = database_path('migrations');
      $directories = glob($mainPath . '/*' , GLOB_ONLYDIR);
      $paths = array_merge([$mainPath], $directories);
      $this->loadMigrationsFrom($paths);  
      // Register Telescope only in local environment
      if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
      }
    }
}
