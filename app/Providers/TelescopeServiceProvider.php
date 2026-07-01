<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night();
      $this->hideSensitiveRequestDetails();
      $isLocal = $this->app->environment('local');
      Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
        if ($isLocal) {
          return true;
        }
      });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
      if ($this->app->environment('local')) {
        return;
      }
      Telescope::hideRequestParameters(['_token']);
      Telescope::hideRequestHeaders([
        'cookie',
        'x-csrf-token',
        'x-xsrf-token',
      ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
      Gate::define('viewTelescope', function ($user) {
        if (app()->environment('local')) {
          return true;
        }
        return false;
      });
    }

        /**
     * Tag Telescope entries for better organization
     */
    protected function tagTelescopeEntries(): void
    {
      Telescope::tag(function (IncomingEntry $entry) {
        $tags = [];
        $tags[] = 'type:' . $entry->type;
        if ($entry->type === 'request') {
          $tags[] = 'status:' . $entry->content['response_status'];
          if ($entry->content['duration'] > 1000) {
            $tags[] = 'slow';
          }
        }
        if ($entry->type === 'query' && $entry->content['slow']) {
          $tags[] = 'slow-query';
        }
        if ($entry->type === 'exception') {
          $tags[] = 'severity:' . $entry->content['class'];
        }
        return $tags;
      });
    }
}
