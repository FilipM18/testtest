<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Password;

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
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

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
Route::get('/ProductInfo', function () {
    return view('ProductInfo');
})->name('ProductInfo');

// Cart Routes
Route::get('/ShoppingCart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/populate', [CartController::class, 'populate'])->name('cart.populate');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/CheckoutPage', function () {
        return view('CheckoutPage');
    });
    
    // Admin Routes
    Route::get('/AdminOrderManagment', function () {
        return view('AdminOrderManagment');
    });
    
    Route::get('/AdminProductManagment', function () {
        return view('AdminProductManagment');
    });
});