<?php

namespace Bavix\CupKit\Providers;

use Bavix\CupKit\Client;
use Bavix\CupKit\ClientCredentials;
use Bavix\CupKit\Commands\CDNCommand;
use Bavix\CupKit\Identity;
use Bavix\CupKit\Storage\CupAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class CupKitServiceProvider extends ServiceProvider
{
    
    public const SINGLETON_CLIENT_CREDENTIALS = 'bavix.cupkit::client_credentials';
    public const SINGLETON_IDENTITY = 'bavix.cupkit::identity';
    public const SINGLETON_CLIENT = 'bavix.cupkit::client';

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->registerConfigs();
        $this->registerCommands();
        $this->registerSingletons();
        $this->registerStorage();
    }

    /**
     * @return void
     */
    protected function registerCommands(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([CDNCommand::class]);
    }

    /**
     * @return void
     */
    protected function registerSingletons(): void
    {
        $this->app->singleton(self::SINGLETON_CLIENT_CREDENTIALS, function () {
            $class = config('corundum.client_credentials');
            return new $class(
                \config('corundum.base_url', ''),
                \config('corundum.client_id', ''),
                \config('corundum.client_secret', '')
            );
        });

        $this->app->singleton(self::SINGLETON_IDENTITY, function () {
            $class = config('corundum.identity');
            return new $class(
                app(self::SINGLETON_CLIENT_CREDENTIALS),
                \config('corundum.username', ''),
                \config('corundum.password', '')
            );
        });

        $this->app->singleton(self::SINGLETON_CLIENT, function () {
            $class = config('corundum.client');
            return new $class(app(self::SINGLETON_IDENTITY));
        });
    }

    /**
     * @return void
     */
    protected function registerStorage(): void
    {
        Storage::extend('corundum', function ($app, $config) {
            return new Filesystem(new CupAdapter($app, $config));
        });
    }

    /**
     * @return void
     */
    protected function registerConfigs(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            \dirname(__DIR__) . '/config/config.php' => config_path('corundum.php'),
        ], 'laravel-cupkit-config');
    }

}
