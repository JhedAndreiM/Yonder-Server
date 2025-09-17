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
        View::share('unreadCount', 0);
        View::share('notifications', collect());

        // Composer for all views (you already had this, extended safely)
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();

                // last 10 notifications for the dropdown (keeps limit)
                $rawNotifications = DB::table('notifications')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                $notifications = $rawNotifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'is_read' => (bool) $notification->is_read,
                        'time_ago' => Carbon::parse($notification->created_at)->diffForHumans(),
                    ];
                });

                // UNREAD COUNT: separate query (no limit)
                $unreadCount = (int) DB::table('notifications')
                    ->where('user_id', $userId)
                    ->where(function ($q) {
                        // support both boolean and integer storage
                        $q->where('is_read', false)->orWhere('is_read', 0);
                    })
                    ->count();

                // pass to all views
                $view->with('notifications', $notifications)
                     ->with('unreadCount', $unreadCount);
            } else {
                // ensure defaults for guests (redundant because of View::share, but explicit)
                $view->with('notifications', collect())
                     ->with('unreadCount', 0);
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
