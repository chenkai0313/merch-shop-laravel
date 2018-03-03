<?php

namespace Modules\Backend\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Backend\Services\AdminService;
use Modules\Backend\Services\AdvService;
use Modules\Backend\Services\CommentService;
use Modules\Backend\Services\LikeService;
use Modules\Backend\Services\OrderService;
use Modules\Backend\Services\ProductService;
use Modules\Backend\Services\RbacService;
use Modules\Backend\Services\AdminLogService;
use Modules\Backend\Services\ConfigService;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->addFacade();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
    }

    /**
     * 门面注册
     */
    public function addFacade()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('AdminService', \Modules\Backend\Facades\AdminFacade::class);
        $loader->alias('RbacService', \Modules\Backend\Facades\RbacFacade::class);
        $loader->alias('AdminLogService', \Modules\Backend\Facades\AdminLogFacade::class);
        $loader->alias('AdvService', \Modules\Backend\Facades\AdvFacade::class);
        $loader->alias('ConfigService', \Modules\Backend\Facades\ConfigFacade::class);
        $loader->alias('LikeService', \Modules\Backend\Facades\LikeFacade::class);
        $loader->alias('CommentService', \Modules\Backend\Facades\CommentFacade::class);
        $loader->alias('ProductService', \Modules\Backend\Facades\ProductFacade::class);
        $loader->alias('OrderService', \Modules\Backend\Facades\OrderFacade::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AdminService', function () {
            return new AdminService();
        });
        $this->app->singleton('RbacService', function () {
            return new RbacService();
        });
        $this->app->singleton('AdminLogService', function () {
            return new AdminLogService();
        });
        $this->app->singleton('AdvService', function () {
            return new AdvService();
        });
        $this->app->singleton('ConfigService', function () {
            return new ConfigService();
        });
        $this->app->singleton('LikeService', function () {
            return new LikeService();
        });
        $this->app->singleton('CommentService', function () {
            return new CommentService();
        });
        $this->app->singleton('ProductService', function () {
            return new ProductService();
        });
        $this->app->singleton('OrderService', function () {
            return new OrderService();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('backend.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'backend'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/backend');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/backend';
        }, \Config::get('view.paths')), [$sourcePath]), 'backend');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/backend');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'backend');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'backend');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
