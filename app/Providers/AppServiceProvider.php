<?php

namespace App\Providers;

use App\Models\SettingApp;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (Schema::hasTable('setting_apps')) {
            View::share('setting', SettingApp::first());
        } else {
            View::share('setting', null);
        }
    }
}
