<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\RoleMiddleware; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CropImageController;
use App\Http\Controllers\UserImportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\CustomMessageController;
use App\Http\Controllers\FeaturedImageController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\FaqCategoryController;
use App\Http\Controllers\FaqController;




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
    Route::get('/Listings', [ProductController::class, 'dashboardForUserSeller'])->name('listing.seller');
    Route::post('/products/saveGcashReceipt/{id}', [CartController::class, 'saveGcashReceipt'])->name('gcash.receipt');
    Route::post('/products/RemoveGcashReceipt/{id}', [CartController::class, 'removeGcashReceipt'])->name('gcash.receiptRemove');
    Route::post('/products/updateSeller', [CartController::class, 'updateSeller'])->name('products.updateSeller');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');

});

// Middleware for Orgs
Route::middleware(['auth', RoleMiddleware::class . ':organization'])->group(function () {
    Route::get('/organization/dashboard', [OrganizationController::class, 'dashboard'])->name('organization.dashboard');
    Route::post('/products/update', [OrganizationController::class, 'update'])->name('products.update');
    //Route::get('/orgReport', function () {return view('organization/orgReport');})->name('org.report');
    // Route::get('/orgPage', function () {return view('organization/orderPage');})->name('order.page');
    //Route::get('/Reviews', function () {return view('organization/reviews');})->name('reivew.page');
    Route::get('/Reviews', [OrganizationController::class, 'reviews'])->name('review.page');
    Route::get('/organization/reviews/search', [OrganizationController::class, 'searchReviews'])->name('reviews.search');
    Route::get('/Orders', [OrganizationController::class, 'orggetAllNotCartItems'])->name('order.page');
    Route::get('/chart', [OrganizationController::class, 'showChart'])->name('org.report');
    Route::get('/ListOfProduct', function () {
        return view('viewListedItems');})->name('viewListedItems.page');
    Route::POST('/generate-pdf', [PDFController::class, 'generate'])->name('generate.pdf');
    Route::POST('/products/update-stock-settings', [OrganizationController::class, 'updateStockSettings'])->name('update.stock');
    Route::delete('/products/{id}', [OrganizationController::class, 'destroy'])->name('products.destroy');

});

// Middleware for Admin
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/approve/{id}', [AdminController::class, 'approveProduct'])->name('admin.approve');
    Route::post('/admin/reject/{id}', [AdminController::class, 'reject'])->name('admin.reject');
    Route::post('/admin/disable/', [AdminController::class, 'updateDisabledButton'])->name('admin.disableButtons');
    Route::post('/admin/product-policy/', [AdminController::class, 'productPolicy'])->name('admin.productPolicy');
    Route::post('/admin/voucher/', [AdminController::class, 'addVoucherList'])->name('admin.voucher');
    Route::post('/admin/credit/', [AdminController::class, 'editCreditPercentage'])->name('admin.credit');
    Route::post('/admin/featured/upload', [FeaturedImageController::class, 'addFeaturedImage'])->name('admin.featured.upload');
    Route::delete('/admin/featured/{id}', [FeaturedImageController::class, 'destroy'])->name('admin.featured.delete');
    Route::get('/admin/import-users', [UserImportController::class, 'showForm'])->name('show.upload.form');
    Route::post('/admin/import-users', [UserImportController::class, 'upload'])->name('upload.users');
    Route::post('/admin/change-role', [AdminController::class, 'changeUserRole'])->name('admin.changeRole');
    Route::delete('/admin/reports/{id}/allow', [AdminController::class, 'allowReport']);
    Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct']);
    Route::post('/admin/add-college', [AdminController::class, 'addCollege'])->name('admin.addCollege');
    Route::post('/admin/update-college/{id}', [AdminController::class, 'updateCollege'])->name('admin.updateCollege');
    Route::delete('/admin/delete-college/{id}', [AdminController::class, 'deleteCollege'])->name('admin.deleteCollege');
    Route::post('/admin/add-Student-Org', [AdminController::class, 'addStudOrg'])->name('admin.addStudOrg');
    Route::post('/admin/update-Student-Org/{id}', [AdminController::class, 'updateStudOrg'])->name('admin.StudOrg');
    Route::delete('/admin/delete-student-org/{id}', [AdminController::class, 'deleteStudOrg'])->name('admin.deleteStudOrg');
    Route::post('/faq-categories', [FaqCategoryController::class, 'store'])->name('faq-category.store');
    Route::post('/faq-categories-update/{id}', [FaqCategoryController::class, 'update'])->name('faq-category.update');
    Route::delete('/faq-categories-delete/{id}', [FaqCategoryController::class, 'delete'])->name('faq-category.delete');
    Route::get('/faq-categories-table', [FaqCategoryController::class, 'table'])->name('faq-category.table');
    Route::post('/admin/faq', [FaqController::class, 'store'])->name('faq.store');
    Route::delete('/admin/faq/{faqQuestion}', [FaqController::class, 'destroy'])->name('faq.destroy');
    Route::put('/admin/faq/{faqQuestion}', [FaqController::class, 'update'])->name('faq.update');


});

// Middleware for Orgs and Studnts
Route::middleware(['auth', RoleMiddleware::class .':student,organization,admin'])->group(function () {
    Route::get('/cart/{id}/card', [CartController::class, 'getCardPartial'])->name('cart.card');
    Route::get('/redirect-home', function () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard');
            } elseif ($user->role === 'organization') {
                return redirect()->route('organization.dashboard');
            }
            elseif($user->role === 'admin'){
                return redirect()->route('admin.dashboard');
            }
        }
        
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    })->name('custom.home');

    
   Route::get('/accountsPage', function () {
    return view('admin/accountsPage');
    })->name('accounts.pages');



    Route::put('/profile', [CropImageController::class, 'update'])->name('profile.update');
    Route::post('/PhoneNumber', [CropImageController::class, 'sendSmsForOTP'])->name('profile.phoneNumber');
    Route::get('/Profile-Setting', function () {
    return view('profileSettings');
    })->name('account.page');

    
 });

Route::middleware(['auth', RoleMiddleware::class .':student,organization'])->group(function () {
    // for creating listing 
    Route::get('/create-listing', function () {
        return view('createListing');
    })->name('create.listing');

    // for products page
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/product-fileupload', [ProductController::class, 'uploadFile'])->name('products.sirRoss');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


   


    //profile redirects
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.page');
    
    Route::get('/my-listings', function () {
        return view('listings');
    })->name('profileListings.page');
    
    Route::get('/Vouchers', [VoucherController::class, 'showVoucher'])->name('show.vouchers');
    Route::post('/Voucher/Redeem', [VoucherController::class, 'redeemVoucher'])->name('redeem.vouchers');
    
    
    // cart
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    //check out all
    Route::post('/cart/checkout-all', [CartController::class, 'checkoutAll'])->name('cart.checkoutAll');
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
   Route::post('/cart/{id}/Update Status', [CartController::class, 'confirmGcashPayment'])->name('cart.confirmPayment');

   // confirm ni buyer yung order
   Route::post('/cart/{id}/OrderReceivedDelivered', [CartController::class, 'orderReceivedDelivered'])->name('cart.orderReceivedDelivered');
   Route::post('/delete', [ProductController::class, 'destroyListing'])->name('delete.listing');
    // para to sa reviews
    Route::post('/submit-review', [ReviewController::class, 'store'])->name('review.store');
   // account page



   Route::get('/accountsPage', function () {
    return view('organization/accountsPage');
    })->name('accounts.page');



     Route::post('/Delete', [CropImageController::class, 'deleteAvatar'])->name('delete.avatar');
    Route::post('/Update', [CropImageController::class, 'cropImageUploadAjax'])->name('update.avatar');
    Route::post('/UpdateUserInfo', [CropImageController::class, 'updateUserInfo'])->name('update.userInfo');

    // update user passwrod
    Route::post('/update-password', [UserImportController::class, 'updatePassword'])->name('profile.update-password');

    Route::post('/chatify/send', [CustomMessageController::class, 'send'])->name('send.message');

   

});
Route::middleware(['auth', RoleMiddleware::class .':admin,organization'])->group(function () {
    Route::get('/accountsPage', function () {
    return view('admin/accountsPage');
    })->name('accounts.page');
});




// Role selection
Route::get('/select-role', [AuthController::class, 'selectRole'])->name('select.role');
Route::get('/select-role/Log-in_First!', [AuthController::class, 'selectRole'])->name('login');

// Login page based on role
Route::get('/login/{role}', [AuthController::class, 'showLoginForm'])->name('login.form');

// Login verification
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/', function () {

    $studentInfo = Auth::user();

    if(auth()->user()){
        if($studentInfo->role=='student'){
            return redirect()->route('student.dashboard');
        }
        elseif($studentInfo->role=='organization'){
                return redirect()->route('organization.dashboard');
        }
        elseif($studentInfo->role=='admin'){
                return redirect()->route('admin.dashboard');
        }
    }
    return view('landing');
})->name('landing');

    
    
Route::get('/AboutUs', function () {
    return view('AboutUs');
})->name('about.us');
Route::get('/FAQ', [FaqController::class, 'showFAQ'])->name('FAQs');
Route::get('/faq/search', [FAQController::class, 'search'])->name('faq.search');
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');