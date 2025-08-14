<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Tag;

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

    View::composer('*', function ($view) {
        $view->with('colleges', DB::table('colleges')->orderBy('code')->get());
    });

    View::composer('*', function ($view) {
        $tags = Tag::whereHas('products', function ($query) {
            $query->where('approved', 'yes');
        })
        ->orderBy('name')
        ->get();

        $view->with('tags', $tags);
    });

    View::composer('*', function ($view) {
        $view->with('student_orgs', DB::table('student_orgs')->orderBy('name')->get());
    });

   View::composer('*', function ($view) {
        $globalRatings = DB::table('reviews')
            ->select(
                'product_id',
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as review_count') 
            )
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $view->with('globalRatings', $globalRatings);
    });

    
    View::composer('*', function ($view) {
    $view->with('productPolicies', DB::table('product_policies')->get());
    });
    }
}
