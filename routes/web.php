<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';


Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Route
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Manage Users Routes
    Route::get('/admin/manage-users', [AdminController::class, 'Manageusers'])->name('admin.manage-users');

    // Manage Products Routes
    Route::get('/admin/manage-products', [AdminController::class, 'Manageproducts'])->name('admin.manage-products');

    // Manage Orders Routes
    Route::get('/admin/manage-orders', [AdminController::class, 'Manageorders'])->name('admin.manage-orders');
    
    // Manage Categories Routes
    Route::get('/admin/manage-categories', [AdminController::class, 'manageCategories'])->name('admin.manage-categories');
    Route::get('/admin/create-category', [AdminController::class, 'createCategory'])->name('admin.create-category');
    Route::post('/admin/store-category', [AdminController::class, 'storeCategory'])->name('admin.store-category');
    Route::get('/admin/edit-category/{category}', [AdminController::class, 'editCategory'])->name('admin.edit-category');
    Route::put('/admin/update-category/{category}', [AdminController::class, 'updateCategory'])->name('admin.update-category');
    Route::delete('/admin/destroy-category/{category}', [AdminController::class, 'destroyCategory'])->name('admin.destroy-category');
});




Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');

    // Manage Products Routes
    Route::get('/products', [SellerController::class, 'manageProducts'])->name('manage-products');
    Route::get('/products/create', [SellerController::class, 'addProduct'])->name('add-product');
    Route::post('/products', [SellerController::class, 'storeProduct'])->name('store-product');
    Route::get('/products/{id}/edit', [SellerController::class, 'editProduct'])->name('edit-product');
    Route::put('/products/{id}', [SellerController::class, 'updateProduct'])->name('update-product');
    Route::delete('/products/{id}', [SellerController::class, 'deleteProduct'])->name('delete-product');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    // Display all products
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    
    // Show the details of a product
    Route::get('/customer/product/{product}', [CustomerController::class, 'showProduct'])->name('customer.show-product');
    
    // Show the shopping cart
    Route::get('/customer/cart', [CustomerController::class, 'showCart'])->name('customer.cart');
    
    // Add a product to the cart
    Route::post('/customer/cart/{productId}', [CustomerController::class, 'addToCart'])->name('customer.add-to-cart');
    
    // Checkout and place an order
    Route::get('/customer/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
    
    // Show all orders
    Route::get('/customer/orders', [CustomerController::class, 'showOrders'])->name('customer.orders');
    
    // Show details of a specific order
    Route::get('/customer/order/{orderId}', [CustomerController::class, 'showOrder'])->name('customer.show-order');
});
