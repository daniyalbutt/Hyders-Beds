<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RouteController;
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
    return redirect()->route('login');
})->middleware('auth');

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('customers', CustomerController::class);
    Route::get('/customer/search', [CustomerController::class, 'search'])->name('customer.search');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('products', ProductController::class);
    Route::get('product/import', [ProductController::class, 'import'])->name('product.import');
    Route::post('product/import/update', [ProductController::class, 'importProduct'])->name('product.update');
    Route::get('/products/types/{section}', [ProductController::class, 'getProductionTypes'])->name('product.types');
    Route::get('/products/ranges/{section}', [ProductController::class, 'getRanges'])->name('product.ranges');
    Route::get('/products/list/{section}/{range}', [ProductController::class, 'getProducts'])->name('product.list');
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/items', [OrderController::class, 'addItem'])->name('orders.addItem');
    Route::delete('/orders/{order}/items/{item}', [OrderController::class, 'removeItem'])->name('orders.removeItem');
    Route::post('/orders/{order}/deposit', [OrderController::class, 'addDeposit'])->name('orders.deposit');
    Route::delete('/orders/{order}/deposit/{deposit}', [OrderController::class, 'removeDeposit'])->name('orders.deposit.remove');
    Route::post('/orders/{order}/items/{item}/update-qty', [OrderController::class, 'updateQty'])->name('orders.items.updateQty');
    Route::post('/orders/create-label', [OrderController::class, 'createLabel'])->name('orders.createLabel');
    Route::put('/orders/{order}/items/{item}/description', [OrderController::class, 'updateItemDescription'])->name('orders.updateItemDesc');
    Route::put('/orders/{order}/items/{item}/price', [OrderController::class, 'updateItemPrice'])->name('orders.updateItemPrice');
    Route::get('/products/fabrics/{section}/{range}/{product}', [ProductController::class, 'getFabrics'])->name('product.fabrics');
    Route::get('/products/{section}/{range}/{product}/drawers', [ProductController::class, 'getDrawers'])->name('product.drawers');
    Route::post('/orders/{order}/items/fabric', [OrderController::class, 'addItemFabric'])->name('orders.addItemFabric');
    Route::post('/orders/{order}/toggle-draft', [OrderController::class, 'toggleDraft'])->name('orders.toggleDraft');
    Route::resource('routes', RouteController::class);
    Route::get('route/unassigned-orders', [RouteController::class, 'unassignedOrders'])->name('routes.unassignedOrders');
    Route::post('route/assign-order', [RouteController::class, 'assignOrder'])->name('routes.assignOrder');

});