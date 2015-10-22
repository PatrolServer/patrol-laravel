<?php namespace PatrolServer\PatrolSdk;

use Illuminate\Support\ServiceProvider;
use PatrolSdk\Patrol as CorePatrol;

class PatrolSdkServiceProviderLaravel5 extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([__DIR__ . '/config/config.php' => config_path('patrol-sdk.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'patrol-sdk');

        $this->app['patrolserver.laravel-patrol'] = $this->app->share(function ($app) {
            $key = $app['config']->get('patrol-sdk.patrol_key');
            $secret = $app['config']->get('patrol-sdk.patrol_secret');

            $base_url = $app['config']->get('patrol-sdk.patrol_api_url');

            $patrol = CorePatrol::init();

            $patrol->setApiKey($key);
            $patrol->setApiSecret($secret);

            if ($base_url) {
                $patrol->setApiBaseUrl($base_url);
            }

            return $patrol;
        });

        $this->app->bind('PatrolSdk\Patrol', 'patrolserver.laravel-patrol');
    }
}
