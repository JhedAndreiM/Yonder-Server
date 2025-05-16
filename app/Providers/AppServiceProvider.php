<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $rawNotifications = DB::table('notifications')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $notifications = $rawNotifications->map(function ($notification) {
                return [
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'time_ago' => Carbon::parse($notification->created_at)->diffForHumans(),
                ];
            });

            $view->with('notifications', $notifications);
        }
    });
    }
}
