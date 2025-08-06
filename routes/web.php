<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomerController;
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

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('auth');

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('customers', CustomerController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('products', ProductController::class);
    Route::get('product/import', [ProductController::class, 'import'])->name('product.import');
    Route::post('product/import/update', [ProductController::class, 'importProduct'])->name('product.update');
});
