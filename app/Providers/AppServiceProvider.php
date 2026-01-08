<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Event;
use Illuminate\Support\Facades\View;


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

public function boot()
{
    // Share today's events with all views
    View::composer('*', function ($view) {
        $eventsToday = Event::whereDate('event_date', now()->toDateString())->get();
        $view->with('eventsToday', $eventsToday);
    });
}
}
