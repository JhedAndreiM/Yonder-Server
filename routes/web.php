<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\FeaturedImageController;
use App\Http\Middleware\RoleMiddleware; // Import the middleware class

Route::get('/login.php', function () {
    return view('login');
});


// Middleware for Student
Route::middleware(['auth', RoleMiddleware::class . ':student'])->group(function () {
    Route::get('/mainPage.php', [PageController::class, 'showMainPage']);
    Route::get('/load-products', [PageController::class, 'loadProducts']);
    Route::get('/student/dashboard', [PageController::class, 'showMainPage'])->name('student.dashboard');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/mainPage.php', [WishlistController::class, 'showHeart']);
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
});





// Role selection
Route::get('/select-role', [AuthController::class, 'selectRole'])->name('select.role');

// Login page based on role
Route::get('/login/{role}', [AuthController::class, 'showLoginForm'])->name('login.form');

// Login verification
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/', function () {
    return view('landing');
});


