<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        return view('admin.kitchen.kitchen');
    }

    public function fetchOrders()
    {
        $orders = Order::whereIn('order_status', ['in queue', 'preparing'])->orderByDesc('order_time')->get();

        foreach ($orders as $order) {
            $order->order_time = Carbon::parse($order->order_time)->format('F j, Y, g:i A');

            $orderedItemCount = Order_Item::where('order_id', $order->order_id)->count('order_item_id');

            $order->item_count = $orderedItemCount;
        }

        return response()->json($orders);
    }

    public function fetchOrderItems(Request $request)
    {
        $order_id = $request->input('order_id');

        $orderStatus = Order::where('order_id', $order_id)->value('order_status');

        $orderStatus = ucwords($orderStatus);

        $orderedItems = Order_Item::join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')
            ->where('order_id', $order_id)
            ->get();

        return response()->json([
            'order_status' => $orderStatus,
            'ordered_items' => $orderedItems
        ]);
    }

    public function updateStatusToPreparing(Request $request)
    {
        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $order_id = $request->input('order_id');

        $order = Order::find($order_id);

        if ($order) {
            $order->order_status = "preparing";

            if ($order->save()) {
                $order_status = ucwords($order->order_status);

                $activity = "Order ID [" . $order_id . "] status is now preparing.";
                $log->endLog($user_id, $user_type, $activity);

                return response()->json(['response' => true, 'order_status' => $order_status]);
            } else {
                return response()->json(['response' => false]);
            }
        }
    }

    public function updateStatusToNowServing(Request $request)
    {
        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $order_id = $request->input('order_id');

        $order = Order::find($order_id);

        if ($order) {
            $order->order_status = "now serving";

            if ($order->save()) {
                $order_status = ucwords($order->order_status);

                $activity = "Order ID [" . $order_id . "] status is cooked and ready to serve.";
                $log->endLog($user_id, $user_type, $activity);

                return response()->json(['response' => true, 'order_status' => $order_status]);
            } else {
                return response()->json(['response' => false]);
            }
        }
    }
}
