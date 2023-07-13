<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
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

    //orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.admin');
    Route::get('/make-order', [OrderController::class, 'makeOrder'])->name('make-order.admin');

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
