<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;

use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Module;
use Modules\Admin\Entities\Models;
use Modules\Admin\Entities\UserModels;
use Modules\Report\Entities\PinnedReport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }

        collect(scandir(app_path('Helper')))->filter(function ($file) {
            return Str::contains($file, '.php');
        })->each(function ($item) {
            require_once app_path('Helper') . '/' . $item;
        });

        $this->app->singleton('pinned_reports', function ($app) {
            return PinnedReport::primary()->latest()->limit(5)->get();
        });

        $this->app->singleton('models', function ($app) {
            return array_filter(array_keys(Module::allEnabled()), function ($item) {
                if (Str::endsWith($item, 'Model')) {
                    return $item;
                }
            });
        });

        $this->app->singleton('notifications', function ($app) {
            $notification = Notification::primary()->latest()->limit(20)->get();

            if ($notification) {
                return $notification;
            }
            return [];
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultstringLength(191);

        try {
            $title = Setting::where('key', 'TITLE')->pluck('value')[0];
            if ($title)
                view()->share(compact('title'));

            $settings = Setting::get();
            if ($settings)
                view()->share(compact('settings'));
        } catch (\Exception $e) {
            //
        }
        Paginator::useBootstrap();
    }
}
