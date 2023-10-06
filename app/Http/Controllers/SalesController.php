<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Order_Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        return view("admin.sales.sales");
    }

    public function fetchCategories(Request $request)
    {
        $categories = Menu::distinct('category')
            ->pluck('category');

        return response()->json($categories);
    }

    public function fetchSoldItemsByCategory($soldItems, $category)
    {
        $soldItems = $soldItems->where('menu_items.category', $category);

        return $soldItems;
    }

    public function fetchSoldItemsBySearchedName($soldItems, $search)
    {
        $soldItems = $soldItems->where('menu_items.name', 'LIKE', '%' . $search . '%');

        return $soldItems;
    }

    public function fetchSoldItemsByPaymentStatus($soldItems, $orderType)
    {
        $soldItems = $soldItems->where('orders.order_type', $orderType);

        return $soldItems;
    }

    public function fetchSoldItems(Request $request)
    {
        $yesterday = $request->input('yesterday');
        $filterDate = $request->input('filter');
        $category = $request->input('category');
        $searchItem = $request->input('search');
        $orderType = $request->input('order-type');

        if ($yesterday) {
            $start = Carbon::yesterday()->startOfDay();
            $end = Carbon::yesterday()->endOfDay();

            $formattedDate = Carbon::yesterday()->format("F j, Y");
        } else if ($filterDate) {
            $start = $filterDate . " 00:00:00";
            $end = $filterDate . " 23:59:59";

            $formattedDate = Carbon::parse($filterDate)->format("F j, Y");
        } else {
            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();

            $formattedDate = Carbon::now()->format("F j, Y");
        }

        $soldItems = Order_Item::select('menu_items.name', 'menu_items.category', 'menu_items.description', 'menu_items.price', DB::raw('SUM(order_items.quantity) as sold_quantity'), DB::raw('menu_items.price * SUM(order_items.quantity) as subtotal'))
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('menu_items.name', 'menu_items.category', 'menu_items.description', 'menu_items.price',);

        $soldItems = $soldItems->whereBetween('order_items.created_at', [$start, $end]);

        if ($category) {
            $this->fetchSoldItemsByCategory($soldItems, $category);
        }

        if ($searchItem) {
            $this->fetchSoldItemsBySearchedName($soldItems, $searchItem);
        }

        if ($orderType) {
            $this->fetchSoldItemsByPaymentStatus($soldItems, $orderType);
        }

        $soldItems = $soldItems->get();

        return response()->json(['sold_items' => $soldItems, 'formatted_date' => $formattedDate]);
    }

    public function getTotalSales(Request $request)
    {
        $yesterday = $request->input('yesterday');
        $filterDate = $request->input('filter');

        if ($yesterday) {
            $start = Carbon::yesterday()->startOfDay();
            $end = Carbon::yesterday()->endOfDay();
        } else if ($filterDate) {
            $start = $filterDate . " 00:00:00";
            $end = $filterDate . " 23:59:59";
        } else {
            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();
        }

        $totalDineInSales = Order::select(DB::raw('SUM(total_amount) as total_dine_in'))
            ->where('order_type', 'dine-in')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalTakeOutSales = Order::select(DB::raw('SUM(total_amount) as total_take_out'))
            ->where('order_type', 'take-out')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalSales = Order::select(DB::raw('SUM(total_amount) as total_sales'))
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        return response()->json([$totalDineInSales, $totalTakeOutSales, $totalSales]);
    }
}
