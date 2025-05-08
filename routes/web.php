<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('Home');
});

Route::get('/Home', function () {
    return view('Home');
});

// Authentication Routes
Route::get('/SignIn', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/SignIn', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', function () {
    abort();
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    abort(404);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    abort(404);
})->middleware('guest')->name('password.reset');

// Reset Password Route
Route::post('/reset-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password_hash' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new \Illuminate\Auth\Events\PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// Product Routes
Route::get('/CategoryPage', [CategoryPageController::class, 'index'])->name('category.index');

// Protected Routes (require authentication)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('products/{id}/data', [AdminController::class, 'getProductData'])->name('products.data');


    Route::post('products/{productId}/images/{imageIndex}/ajax', [AdminController::class, 'deleteProductImageAjax'])
    ->name('products.delete-image-ajax');

    
    // Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
});

// Product routes
Route::get('/ProductInfo/{id}', [ProductController::class, 'show'])->name('ProductInfo.show');

// Cart routes
Route::get('/ShoppingCart', [CartController::class, 'index'])->name('cart.index');
Route::post('/ShoppingCart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/ShoppingCart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/ShoppingCart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add.alt');
Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeItem'])->name('cart.remove.item');


Route::get('/test-db', function () {
    try {
        $product = \App\Models\Product::find(901);
        if ($product) {
            return "Found product: " . $product->name;
        } else {
            return "Product not found";
        }
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Review routes
Route::get('/reviews/create/{product_id}', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Checkout routes
Route::get('/CheckoutPage', [CheckoutController::class, 'index'])->name('checkout.legacy');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/complete', [CheckoutController::class, 'completeOrder'])->name('checkout.complete');
Route::get('/order/confirmation/{order}', [CheckoutController::class, 'showConfirmation'])->name('order.confirmation');

Route::get('/test-admin', [AdminController::class, 'products']);

// In routes/web.php
Route::get('/test-image/{id}', function($id) {
    $product = \App\Models\Product::find($id);
    dd([
        'raw_image_url' => $product->image_url,
        'first_image' => $product->first_image,
        'all_images' => $product->all_images,
        'image_type' => gettype($product->image_url),
        'image_exists' => file_exists(public_path($product->first_image))
    ]);
});