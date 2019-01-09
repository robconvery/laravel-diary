<?php

namespace Robconvery\Laraveldiary;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Robconvery\Laraveldiary\Controllers\DiaryDataController;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/assets/js/angularjs/1.7.5' => public_path('js'),
            dirname(__DIR__) . '/assets/js/angularjs/draggable' => public_path('js'),
            dirname(__DIR__) . '/assets/js/diary' => public_path('js'),
            dirname(__DIR__) . '/assets/src' => app_path(),
            dirname(__DIR__) . '/assets/tests' => dirname(app_path()) . '/tests',
            dirname(__DIR__) . '/src/Views' => resource_path('views/diary'),
        ], 'diary');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'diary');
        $this->loadViewsFrom(resource_path('views'), 'app');
        include __DIR__ . '/routes.php';

        App()->bind(DiaryEntryInterface::class, function($app, $params) {
            $data = is_array(current($params)) ? current($params) : null;
            return new FakeDiaryEntry($data);
        });
    }
}
