<?php

namespace App\Providers;

use App\Models\Pengaturan;
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
    public function boot()
    {
        try {
        if (Schema::hasTable('pengaturans')) {
                $setting = Pengaturan::first();

                if ($setting) {
                    // kode Anda
                }
            }
        } catch (\Throwable $e) {
            // Abaikan ketika database belum siap
        }
    }
}
