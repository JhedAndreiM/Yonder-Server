<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\FeaturedImageController;
use App\Http\Middleware\RoleMiddleware; // Import the middleware class



// Middleware for Student
Route::middleware(['auth', RoleMiddleware::class . ':student'])->group(function () {
    Route::get('/mainPage.php', [PageController::class, 'showMainPage']);
    Route::get('/load-products', [PageController::class, 'loadProducts']);
    Route::get('/student/dashboard', [PageController::class, 'showMainPage'])->name('student.dashboard');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/mainPage.php', [WishlistController::class, 'showHeart']);
    Route::get('/wishlist', function () {
        return view('wishlist');})->name('wishlist.page');

    Route::get('/wishlist-Show', [WishlistController::class, 'showWishlist'])->name('show.wishlist');
    Route::get('/student/profile', [CartController::class, 'getAllNotCartItems'])->name('student.profile');
    Route::get('/student/Sales', [CartController::class, 'getAllSales'])->name('student.sales');
    
});

// Middleware for Orgs
Route::middleware(['auth', RoleMiddleware::class . ':organization'])->group(function () {
    Route::get('/organization/dashboard', [OrganizationController::class, 'dashboard'])->name('organization.dashboard');
    
});

// Middleware for Admin
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/approve-product/{id}', [AdminController::class, 'approveProduct'])->name('admin.approve');
    Route::post('/admin/featured/upload', [FeaturedImageController::class, 'addFeaturedImage'])->name('admin.featured.upload');
    
});

// Middleware for Orgs and Studnts
Route::middleware(['auth', RoleMiddleware::class .':student,organization'])->group(function () {
    // for creating listing 
    Route::get('/create-listing', function () {
        return view('createListing');
    })->name('create.listing');

    // for products page
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


    Route::get('/redirect-home', function () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard');
            } elseif ($user->role === 'organization') {
                return redirect()->route('organization.dashboard');
            }
        }
    
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    })->name('custom.home');


    //profile redirects
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.page');
    
    Route::get('/my-listings', function () {
        return view('listings');
    })->name('profileListings.page');
    
    Route::get('/my-vouchers', function () {
        return view('vouchers');
    })->name('vouchers.page');
    
    
    // cart
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    // add to cart
    Route::get('/Cart', [CartController::class, 'showCart'])->name('show.cart');
   // remove from cart
   Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
   // update cart from incart to pending
   Route::post('/cart/{id}/buy', [CartController::class, 'update'])->name('cart.buy');

   //profile
   Route::post('/cart/{id}/cancel', [CartController::class, 'cancel'])->name('cart.cancel');
   Route::post('/cart/{id}/cancelSales', [CartController::class, 'cancelSales'])->name('cart.cancelSales');

   //confirm ni student seller
   Route::post('/cart/{id}/Update Sales', [CartController::class, 'confirmStudentSales'])->name('cart.confirmSales');

   // confirm ni buyer yung order
   Route::post('/cart/{id}/OrderReceivedDelivered', [CartController::class, 'orderReceivedDelivered'])->name('cart.orderReceivedDelivered');
});





// Role selection
Route::get('/select-role', [AuthController::class, 'selectRole'])->name('select.role');
Route::get('/select-role/Log-in_First!', [AuthController::class, 'selectRole'])->name('login');

// Login page based on role
Route::get('/login/{role}', [AuthController::class, 'showLoginForm'])->name('login.form');

// Login verification
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/', function () {
    return view('landing');
});



Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

