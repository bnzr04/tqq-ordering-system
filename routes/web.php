<?php

use App\Http\Controllers\DashboardController;
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
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
});

/*///////////
CASHIER ROUTES
*/ //////////
Route::prefix('cashier')->middleware(['auth', 'user-access:cashier'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'cashierDashboard'])->name('dashboard.cashier');
});
