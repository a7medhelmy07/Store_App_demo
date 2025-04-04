<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CustomerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin API Routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('api.admin.dashboard');
    Route::get('/manage-users', [AdminController::class, 'Manageusers'])->name('api.admin.manage-users');
    Route::get('/manage-products', [AdminController::class, 'Manageproducts'])->name('api.admin.manage-products');
    Route::get('/manage-orders', [AdminController::class, 'Manageorders'])->name('api.admin.manage-orders');
    Route::get('/manage-categories', [AdminController::class, 'manageCategories'])->name('api.admin.manage-categories');
    Route::post('/store-category', [AdminController::class, 'storeCategory'])->name('api.admin.store-category');
    Route::put('/update-category/{category}', [AdminController::class, 'updateCategory'])->name('api.admin.update-category');
    Route::delete('/destroy-category/{category}', [AdminController::class, 'destroyCategory'])->name('api.admin.destroy-category');
});

// Seller API Routes
Route::middleware(['auth:sanctum', 'role:seller'])->prefix('seller')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'index'])->name('api.seller.dashboard');
    Route::get('/products', [SellerController::class, 'manageProducts'])->name('api.seller.manage-products');
    Route::post('/products', [SellerController::class, 'storeProduct'])->name('api.seller.store-product');
    Route::get('/products/{id}/edit', [SellerController::class, 'editProduct'])->name('api.seller.edit-product');
    Route::put('/products/{id}', [SellerController::class, 'updateProduct'])->name('api.seller.update-product');
    Route::delete('/products/{id}', [SellerController::class, 'deleteProduct'])->name('api.seller.delete-product');
});

// Customer API Routes
Route::middleware(['auth:sanctum', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('api.customer.index');
    Route::get('/product/{product}', [CustomerController::class, 'showProduct'])->name('api.customer.show-product');
    Route::get('/cart', [CustomerController::class, 'showCart'])->name('api.customer.cart');
    Route::post('/cart/{productId}', [CustomerController::class, 'addToCart'])->name('api.customer.add-to-cart');
    Route::post('/checkout', [CustomerController::class, 'checkout'])->name('api.customer.checkout');
    Route::get('/orders', [CustomerController::class, 'showOrders'])->name('api.customer.orders');
    Route::get('/order/{orderId}', [CustomerController::class, 'showOrder'])->name('api.customer.show-order');
});
