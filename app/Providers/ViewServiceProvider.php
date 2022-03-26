<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        View::composer('*',function (\Illuminate\View\View $view) {
            $user = auth()->user();
            if ($user) {
                $user->append('avatar');
                $user->load('vessel');
            }
            $view->with('loggedInUser', $user);
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mail_templates');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
