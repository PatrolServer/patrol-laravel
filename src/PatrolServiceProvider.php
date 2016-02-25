<?php namespace PatrolServer\Patrol;

use Illuminate\Support\ServiceProvider;
use RuntimeException;
use PatrolSdk\Patrol as Sdk;
use PatrolServer\Patrol\Console\Commands\Run;

class PatrolServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Instantiate the service provider
     *
     * @param mixed $app
     * @return void
     */
    public function __construct($app) {
        parent::__construct($app);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([__DIR__ . '/config/config.php' => config_path('patrol.php')]);

        if (!$this->app->routesAreCached())
            require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'patrol');

        $this->registerSdk();
        $this->registerCommands();
    }

    /**
     * Register the SDK service.
     *
     * @return void
     */
    private function registerSdk() {
        $this->app['ps.sdk'] = $this->app->share(function ($app) {
            $config = $app['config'];

            $sdk = Sdk::init();

            $sdk->setApiKey($config->get('patrol.key'));
            $sdk->setApiSecret($config->get('patrol.secret'));

            $base_url = $config->get('patrol.api_url');
            if ($base_url)
                $sdk->setApiBaseUrl($base_url);

            return $sdk;
        });
    }

    /**
     * Register commands.
     *
     * @return void
     */
    private function registerCommands() {
        $this->app->singleton('ps.cmd.run', function ($app) {
            return new Run;
        });

        $this->commands('ps.cmd.run');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['ps.sdk', 'ps.cmd.run'];
    }

}
