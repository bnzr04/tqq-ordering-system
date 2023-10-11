<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // this function will return the admin dashboard view
    public function adminDashboard()
    {
        return view('admin.dashboard'); // return the admin dashboard view
    }

    // this function will return the cashier dashboard view
    public function cashierDashboard()
    {
        return view('cashier.dashboard'); // return the cashier dashboard view
    }

    public function dashBoardInformation()
    {
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->endOfDay();

        $todayTotalOrder = Order::whereBetween("created_at", [$start, $end])->count();
        $dineInOrder = Order::whereBetween("created_at", [$start, $end])->where("order_type", "dine-in")->count();
        $takeOutOrder = Order::whereBetween("created_at", [$start, $end])->where("order_type", "take-out")->count();
        $itemsInMenu = Menu::count();
        $itemsWithStock = Menu::rightJoin("item_stocks", "menu_items.item_id", "=", "item_stocks.menu_item_id")->count();
        $inQueueOrders = Order::where("order_status", "in queue")->count();
        $preparingOrders = Order::where("order_status", "preparing")->count();

        return response()->json([
            "todayTotalOrder" => $todayTotalOrder,
            "dineInOrder" => $dineInOrder,
            "takeOutOrder" => $takeOutOrder,
            "itemsInMenu" => $itemsInMenu,
            "itemsWithStock" => $itemsWithStock,
            "inQueueOrders" => $inQueueOrders,
            "preparingOrders" => $preparingOrders,
        ]);
    }
}
