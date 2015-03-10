<?php namespace Admsa\Larachet;

use Admsa\Larachet\Console\Commands\LarachetCommand;
use Illuminate\Support\ServiceProvider;
use Admsa\Larachet\Library\Pusher;
use Admsa\Larachet\Library\PushServer;
use Illuminate\Filesystem\Filesystem;
use Admsa\Larachet\Library\JsRenderer;
use Admsa\Larachet\Library\Larachet;

use ZMQContext;

class LarachetServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        $app['router']->group(['prefix' => '_larachet', 'namespace' => 'Admsa\Larachet\Controllers'], function($router) {
            $router->get('assets/js', [
                'uses' => 'AssetController@js', 'as' => 'larachet.assets.js'
            ]);
        });

        if ( ! $app->runningInConsole()) {
            $app['router']->after(function($request, $response) use($app) {
                $content = $response->getContent();
                preg_match ('/\<script(.*)?>/', $content, $matches, PREG_OFFSET_CAPTURE);

                if (false !== ($pos = isset($matches[0][1]) ? $matches[0][1] : false)) {
                    $response->setContent(substr($content, 0, $pos) . (new JsRenderer($app['files']))->renderScript('larachet.assets.js') . substr($content, $pos));
                }
            });
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $larachet = function($app) {
            return new Larachet(new ZMQContext);
        };

        $this->app->bind('Admsa\Larachet\Library\Larachet', $larachet);
        $this->app->bind('larachet', $larachet);

        $this->app->bind('Larachet\PushServer', function($app) {
            return new PushServer(new Pusher);
        });

        $this->app->bind('Admsa\Larachet\Library\JsRenderer', function($app) {
            return new JsRenderer($app['files']);
        });

        $this->app['command.larachet.serve'] = $this->app->share(function($app) {
            return new LarachetCommand;
        });

        $this->commands(['command.larachet.serve']);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['larachet', 'command.larachet.serve'];
    }
}
