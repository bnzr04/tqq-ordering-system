<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    function generateReceipt(Request $request)
    {
        $orderId = $request->input('order_id');
        $cash = $request->input('cash');
        $change = $request->input('change');

        $order = Order::join("users", "orders.cashier_id", "=", "users.id")
            ->where("orders.order_id", $orderId)
            ->first();

        $order->cash_payment = $cash;
        $order->save();

        $items = Order_Item::join("menu_items", "order_items.menu_item_id", "=", "menu_items.item_id")
            ->where("order_items.order_id", $orderId)
            ->get();

        foreach ($items as $item) {
            $item->subtotal = number_format($item->price * $item->quantity, 2, '.');
        }

        return view('layouts.receipt')->with([
            "order_id" => $orderId,
            "daily_order_id" => $order->daily_order_id,
            "order_type" => $order->order_type,
            "table_number" => $order->table_number,
            "cashier_name" => $order->name,
            "cash" => $cash,
            "change" => $change,
            "amount" => $order->total_amount,
            "itemCount" => count($items),
            "items" => $items,
            "print" => false,
        ]);
    }

    function printReceipt(Request $request)
    {
        $orderId = $request->input("order_id");

        $order = Order::join("users", "orders.cashier_id", "=", "users.id")
            ->where("orders.order_id", $orderId)
            ->first();

        if ($order->cash_payment !== null) {
            $change = $order->cash_payment - $order->total_amount;
        } else {
            $change = 0;
        }

        $items = Order_Item::join("menu_items", "order_items.menu_item_id", "=", "menu_items.item_id")
            ->where("order_items.order_id", $orderId)
            ->get();

        foreach ($items as $item) {
            $item->subtotal = number_format($item->price * $item->quantity, 2, '.');
        }

        return view('layouts.receipt')->with([
            "order_id" => $orderId,
            "daily_order_id" => $order->daily_order_id,
            "order_type" => $order->order_type,
            "table_number" => $order->table_number,
            "cashier_name" => $order->name,
            "cash" => $order->cash_payment,
            "change" => number_format($change, 2, '.'),
            "amount" => $order->total_amount,
            "itemCount" => count($items),
            "items" => $items,
            "print" => true,
        ]);
    }
}
