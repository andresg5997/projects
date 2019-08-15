<?php

namespace App\Providers;

use App\Tarea;
use App\Observers\TareaObserver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('es');

        Blade::if('admin', function(){
            if(Auth::user()->type === 'admin'){
                return true;
            }
            return false;
        });

        Tarea::observe(TareaObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
