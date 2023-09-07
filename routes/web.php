<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StockController;
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
    Route::post('/update-item-info', [MenuController::class, 'updateItemInformation'])->name('update-item-info.admin');
    Route::post('/delete-item-info', [MenuController::class, 'removeItemInformation'])->name('delete-item-info.admin');

    //stocks routes
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.admin');
    Route::get('/fetch-items-and-stocks', [StockController::class, 'fetchItemsAndStocks'])->name('fetch-items-and-stocks.admin');
    Route::post('/add-item-stock', [StockController::class, 'addOrRemoveStockQuantity'])->name('add-item-stock.admin');

    //orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.admin');
    Route::get('/next-order-id', [OrderController::class, 'nextOrderId'])->name('next-order-id.admin');
    Route::get('/make-order', [OrderController::class, 'makeOrder'])->name('make-order.admin');
    Route::post('/submit-order', [OrderController::class, 'submitOrder'])->name('submit-order.admin');
    Route::get('/fetch-orders', [OrderController::class, 'fetchOrders'])->name('fetch-orders.admin');
    Route::get('/fetch-orders-by-date', [OrderController::class, 'fetchOrdersByDate'])->name('fetch-orders-by-date.admin');
    Route::get('/fetch-ordered-items-of-order', [OrderController::class, 'getOrderedItemsOfOrder'])->name('fetch-ordered-items-of-order.admin');
    Route::get('/add-new-item-to-order', [OrderController::class, 'addNewItemToOrder'])->name('add-new-item-to-order.admin');
    Route::post('/update-item-status-complete', [OrderController::class, 'updateItemStatusToComplete'])->name('update-item-status-complete.admin');
    Route::post('/remove-item-to-order', [OrderController::class, 'removeItemQuantity'])->name('remove-item-to-order.admin');

    //kitchen routes
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.admin');
    Route::get('/search-item-name', [MenuController::class, 'searchItemByName'])->name('search-item-name.admin');
    Route::get('/fetch-kitchen-orders', [KitchenController::class, 'fetchOrders'])->name('fetch-kitchen-orders.admin');
    Route::get('/fetch-ordered-items', [KitchenController::class, 'fetchOrderItems'])->name('fetch-ordered-items.admin');
    Route::post('/update-status-preparing', [KitchenController::class, 'updateStatusToPreparing'])->name('update-status-preparing.admin');
    Route::post('/update-status-now-serving', [KitchenController::class, 'updateStatusToNowServing'])->name('update-status-now-serving.admin');

    //payment routes
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.admin');
    Route::get('/fetch-unpaid-orders', [PaymentController::class, 'fetchUnpaidOrders'])->name('fetch-unpaid-orders.admin');

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
