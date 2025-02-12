<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $url = parse_url(url('/'));
        if($url['scheme'] == 'https'){
            URL::forceScheme('https');
        }
        Carbon::setLocale(LC_TIME, $this->app->getLocale());
    }
}
