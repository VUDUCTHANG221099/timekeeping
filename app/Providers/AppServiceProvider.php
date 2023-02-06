<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        if (config('app.use_ssl', false)) {
            URL::forceScheme('https');
        }
        
        $data=[
            'base_url'=>'assets',
            'carbon'=>'Carbon\Carbon',
            'str'=>"Illuminate\Support\Str",
            'count'=>1,
        ];
        View::share($data);
    }
}
