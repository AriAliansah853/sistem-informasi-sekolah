<?php

namespace App\Providers;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        try {
            $pengaturan = null;

            if (Schema::hasTable('pengaturans')) {
                $pengaturan = Pengaturan::first();
            }

            View::share('pengaturan', $pengaturan);
            View::share('setting', $pengaturan);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
