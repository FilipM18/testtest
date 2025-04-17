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



// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/CheckoutPage', function () {
        return view('CheckoutPage');
    });
    
    // Admin Routes
    Route::get('/AdminOrderManagement', function () {
        return view('AdminOrderManagement');
    });
    
    Route::get('/AdminProductManagement', function () {
        return view('AdminProductManagement');
    });
});

// Product routes
Route::get('/ProductInfo/{id}', [ProductController::class, 'show'])->name('ProductInfo.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/ShoppingCart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/ShoppingCart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/ShoppingCart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/ShoppingCart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/ShoppingCart/populate', [CartController::class, 'populate'])->name('cart.populate');
});


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


