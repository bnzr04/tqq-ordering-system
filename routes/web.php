<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

/*///////////
GUEST/NOT AUTHENTICATED ROUTES
*/ //////////
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

/*///////////
ADMIN ROUTES
*/ //////////
Route::prefix('admin')->middleware(['auth', 'user-access:admin'])->group(function () {
    //dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');

    //menu routes
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.admin');
    Route::get('/filter-menu', [MenuController::class, 'filterItemByCategory'])->name('filter-menu.admin');
    Route::get('/fetch-menu', [MenuController::class, 'fetchItem'])->name('fetch-menu.admin');
    Route::post('/save-menu-item', [MenuController::class, 'saveItem'])->name('save-item.admin');

    //orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.admin');
    Route::get('/make-order', [OrderController::class, 'makeOrder'])->name('make-order.admin');
    Route::post('/submit-order', [OrderController::class, 'submitOrder'])->name('submit-order.admin');
    Route::get('/fetch-orders', [OrderController::class, 'fetchOrders'])->name('fetch-orders.admin');

    //kitchen routes
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.admin');

    //logs routes
    Route::get('/logs', [LogController::class, 'index'])->name('logs.admin');
    Route::get('/view-logs', [LogController::class, 'view'])->name('logs-view.admin');
});

/*///////////
CASHIER ROUTES
*/ //////////
Route::prefix('cashier')->middleware(['auth', 'user-access:cashier'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'cashierDashboard'])->name('dashboard.cashier');
});
