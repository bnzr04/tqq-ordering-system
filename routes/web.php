<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth')->group(function () {
    Route::get('/generate-receipt', [ReceiptController::class, 'generateReceipt'])->name('generate-receipt');
    Route::get('/print-receipt', [ReceiptController::class, 'printReceipt'])->name('print-receipt');
});

/*///////////
ADMIN ROUTES
*/ //////////

Route::prefix('admin')->middleware(['auth', 'user-access:admin'])->group(function () {
    //dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
    Route::get('/dashboard-info', [DashboardController::class, 'dashBoardInformation'])->name('dashboard-info.admin');

    //users routes
    Route::get('/users', [UserController::class, 'viewUsers'])->name('users.admin');
    Route::get('/get-users', [UserController::class, 'index'])->name('get-users.admin');
    Route::get('/get-user-info', [UserController::class, 'getUserInfo'])->name('get-user-info.admin');
    Route::post('/add-user-info', [UserController::class, 'addNewUserInfo'])->name('add-user-info.admin');
    Route::put('/update-user-info', [UserController::class, 'updateUserInfo'])->name('update-user-info.admin');
    Route::put('/update-user-password', [UserController::class, 'changeUserPassword'])->name('update-user-password.admin');
    Route::delete('/delete-user', [UserController::class, 'deleteUser'])->name('delete-user.admin');

    //menu routes
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.admin');
    Route::get('/filter-item-by-category', [MenuController::class, 'filterItemByCategory'])->name('filter-item-by-category.admin');
    Route::get('/fetch-items', [MenuController::class, 'fetchItems'])->name('fetch-items.admin');
    Route::get('/fetch-categories', [MenuController::class, 'fetchCategories'])->name('fetch-categories.admin');
    Route::post('/save-menu-item', [MenuController::class, 'saveItem'])->name('save-item.admin');
    Route::post('/update-item-info', [MenuController::class, 'updateItemInformation'])->name('update-item-info.admin');
    Route::post('/delete-item-info', [MenuController::class, 'deleteItemInformation'])->name('delete-item-info.admin');

    //stocks routes
    Route::get('/filter-stock-by-range', [StockController::class, 'filterStockByRange'])->name('filter-stock-by-range.admin');
    Route::get('/fetch-items-and-stocks', [StockController::class, 'fetchItemsAndStocks'])->name('fetch-items-and-stocks.admin');
    Route::post('/add-item-stock', [StockController::class, 'addOrRemoveStockQuantity'])->name('add-item-stock.admin');
    Route::post('/generate-item-stocks-report', [ReportController::class, 'itemStockReportExport'])->name('generate-item-stocks-report.admin');

    //orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.admin');
    Route::get('/next-order-id', [OrderController::class, 'nextOrderId'])->name('next-order-id.admin');
    Route::get('/make-order', [OrderController::class, 'makeOrder'])->name('make-order.admin');
    Route::post('/submit-order', [OrderController::class, 'submitOrder'])->name('submit-order.admin');
    Route::get('/fetch-orders', [OrderController::class, 'fetchOrders'])->name('fetch-orders.admin');
    Route::get('/fetch-orders-by-date', [OrderController::class, 'fetchOrdersByDate'])->name('fetch-orders-by-date.admin');
    Route::get('/fetch-ordered-items-of-order', [OrderController::class, 'getOrderedItemsOfOrder'])->name('fetch-ordered-items-of-order.admin');
    Route::get('/add-new-item-to-order', [OrderController::class, 'addNewItemToOrder'])->name('add-new-item-to-order.admin');
    Route::get('/update-order-status-complete', [OrderController::class, 'updateOrderToComplete'])->name('update-order-status-complete.admin');
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
    Route::post('/make-order-paid', [PaymentController::class, 'markOrderAsPaid'])->name('make-order-paid.admin');

    //sales routes
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.admin');
    Route::get('/fetch-categories', [SalesController::class, 'fetchCategories'])->name('fetch-categories.admin');
    Route::get('/fetch-sold-items', [SalesController::class, 'fetchSoldItems'])->name('fetch-sold-items.admin');
    Route::get('/get-sales-amount', [SalesController::class, 'getTotalSales'])->name('get-sales-amount.admin');
    Route::post('/add-cash', [SalesController::class, 'addCash'])->name('add-cash.admin');
    Route::post('/generate-sales-report', [ReportController::class, 'salesReportExport'])->name('generate-sales-report.admin');

    //logs routes
    Route::get('/logs', [LogController::class, 'index'])->name('logs.admin');
    Route::get('/view-logs', [LogController::class, 'view'])->name('logs-view.admin');
});

/*///////////
CASHIER ROUTES
*/ //////////
Route::prefix('cashier')->middleware(['auth', 'user-access:cashier'])->group(function () {
    //dashboard
    Route::get('/dashboard-info', [DashboardController::class, 'dashBoardInformation'])->name('dashboard-info.cashier');
    Route::get('/dashboard', [DashboardController::class, 'cashierDashboard'])->name('dashboard.cashier');

    //menu routes
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.cashier');
    Route::get('/filter-item-by-category', [MenuController::class, 'filterItemByCategory'])->name('filter-item-by-category.cashier');
    Route::get('/fetch-items', [MenuController::class, 'fetchItems'])->name('fetch-items.cashier');
    Route::get('/fetch-categories', [MenuController::class, 'fetchCategories'])->name('fetch-categories.cashier');
    Route::post('/save-menu-item', [MenuController::class, 'saveItem'])->name('save-item.cashier');
    Route::post('/update-item-info', [MenuController::class, 'updateItemInformation'])->name('update-item-info.cashier');

    //stocks routes
    Route::get('/filter-stock-by-range', [StockController::class, 'filterStockByRange'])->name('filter-stock-by-range.cashier');
    Route::get('/fetch-items-and-stocks', [StockController::class, 'fetchItemsAndStocks'])->name('fetch-items-and-stocks.cashier');
    Route::post('/add-item-stock', [StockController::class, 'addOrRemoveStockQuantity'])->name('add-item-stock.cashier');
    Route::post('/generate-item-stocks-report', [ReportController::class, 'itemStockReportExport'])->name('generate-item-stocks-report.cashier');

    //orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.cashier');
    Route::get('/next-order-id', [OrderController::class, 'nextOrderId'])->name('next-order-id.cashier');
    Route::get('/make-order', [OrderController::class, 'makeOrder'])->name('make-order.cashier');
    Route::post('/submit-order', [OrderController::class, 'submitOrder'])->name('submit-order.cashier');
    Route::get('/fetch-orders', [OrderController::class, 'fetchOrders'])->name('fetch-orders.cashier');
    Route::get('/fetch-orders-by-date', [OrderController::class, 'fetchOrdersByDate'])->name('fetch-orders-by-date.cashier');
    Route::get('/fetch-ordered-items-of-order', [OrderController::class, 'getOrderedItemsOfOrder'])->name('fetch-ordered-items-of-order.cashier');
    Route::get('/add-new-item-to-order', [OrderController::class, 'addNewItemToOrder'])->name('add-new-item-to-order.cashier');
    Route::get('/update-order-status-complete', [OrderController::class, 'updateOrderToComplete'])->name('update-order-status-complete.cashier');
    Route::post('/remove-item-to-order', [OrderController::class, 'removeItemQuantity'])->name('remove-item-to-order.cashier');

    //kitchen routes
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.cashier');
    Route::get('/search-item-name', [MenuController::class, 'searchItemByName'])->name('search-item-name.cashier');
    Route::get('/fetch-kitchen-orders', [KitchenController::class, 'fetchOrders'])->name('fetch-kitchen-orders.cashier');
    Route::get('/fetch-ordered-items', [KitchenController::class, 'fetchOrderItems'])->name('fetch-ordered-items.cashier');
    Route::post('/update-status-preparing', [KitchenController::class, 'updateStatusToPreparing'])->name('update-status-preparing.cashier');
    Route::post('/update-status-now-serving', [KitchenController::class, 'updateStatusToNowServing'])->name('update-status-now-serving.cashier');

    //payment routes
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.cashier');
    Route::get('/fetch-unpaid-orders', [PaymentController::class, 'fetchUnpaidOrders'])->name('fetch-unpaid-orders.cashier');
    Route::post('/make-order-paid', [PaymentController::class, 'markOrderAsPaid'])->name('make-order-paid.cashier');

    //sales routes
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.cashier');
    Route::get('/fetch-categories', [SalesController::class, 'fetchCategories'])->name('fetch-categories.cashier');
    Route::get('/fetch-sold-items', [SalesController::class, 'fetchSoldItems'])->name('fetch-sold-items.cashier');
    Route::get('/get-sales-amount', [SalesController::class, 'getTotalSales'])->name('get-sales-amount.cashier');
    Route::post('/add-cash', [SalesController::class, 'addCash'])->name('add-cash.cashier');
    Route::post('/generate-sales-report', [ReportController::class, 'salesReportExport'])->name('generate-sales-report.cashier');
});
