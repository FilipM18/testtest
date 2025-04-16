<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\CartController;
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

Route::get('/', function () {
    return view('Home');
});

Route::get('/Home', function () {
    return view('Home');
});

Route::get('/AdminOrderManagment', function () {
    return view('AdminOrderManagment');
});

Route::get('/AdminProductManagment', function () {
    return view('AdminProductManagment');
});

Route::get('/CategoryPage', [CategoryPageController::class, 'index']);

Route::get('/ProductInfo', function () {
    return view('ProductInfo');
})->name('ProductInfo');

Route::get('/CheckoutPage', function () {
    return view('CheckoutPage');
});

Route::get('/ShoppingCart', function () {
    return view('ShoppingCart');
});

Route::get('/SignIn', function () {
    return view('SignIn');
});

Route::get('/ShoppingCart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/populate', [CartController::class, 'populate'])->name('cart.populate');
