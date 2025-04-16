<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrganizationController;



Route::get('/login.php', function () {
    return view('login');
    });
Route::get('/mainPage.php', [PageController::class, 'showMainPage']);
Route::get('/load-products', [PageController::class, 'loadProducts']);

// role selection to
Route::get('/select-role', [AuthController::class, 'selectRole'])->name('select.role');

// login page based sa role nila
Route::get('/login/{role}', [AuthController::class, 'showLoginForm'])->name('login.form');

// pag veverifyan ng login
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// dashboard ng mga role
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/organization/dashboard', [OrganizationController::class, 'dashboard'])->name('organization.dashboard');
Route::get('/student/dashboard', [PageController::class, 'showMainPage'])->name('student.dashboard');

// para sa listing page
Route::get('/student/create-listing', function () {
    return view('createListing');
})->name('create.listing');

Route::get('/', function () {
    return view('landing');
});

Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');