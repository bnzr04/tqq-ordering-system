<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KitchenController extends Controller
{
    public function index() //this function will return the kitchen view
    {
        if (Auth::user()->type === "admin") {
            return view('admin.kitchen.new-kitchen');
        } else {
            return view('cashier.kitchen.new-kitchen');
        }
    }

    public function fetchOrders() //this function will fetch the orders from database
    {
        $orders = Order::whereIn('order_status', ['in queue', 'preparing'])
            ->get(); //retrieve the orders where order_status is 'in queue' & 'preparing'

        foreach ($orders as $order) { //for every orders that retrieved
            $order->created_at = Carbon::parse($order->created_at)->format('F j, Y, g:i A'); //format the date value of created_at in readable format 

            $orderedItemCount = Order_Item::where('order_id', $order->order_id)->count('order_item_id'); //the total count of the items in an order

            $order->item_count = $orderedItemCount; //store the items count

            $dateToday = Carbon::now()->format("Y-m-d");

            $orderedItems = Order_Item::join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')
                ->where('order_items.order_id', $order->order_id)
                ->whereIn('order_items.status', ['in queue', 'preparing'])
                ->get();

            $order->ordered_items = $orderedItems;
        }

        return response()->json(['order' => $orders]);
    }

    // public function fetchOrders() //this function will fetch the orders from database
    // {
    //     $newOrderItems = Order_Item::whereIn('status', ['in queue'])
    //         ->whereIn('created_at', function ($query) {
    //             $query->select('created_at')
    //                 ->from('order_items')
    //                 ->whereIn('status', ['in queue'])
    //                 ->groupBy('created_at')
    //                 ->havingRaw('COUNT(*) > 1');
    //         })
    //         ->get();

    //     return response()->json(['order' => $newOrderItems]);
    // }

    public function fetchOrderItems(Request $request)
    {
        $order_id = $request->input('order_id');

        $order = Order::where('order_id', $order_id)->first();

        $dailyOrderId = $order->daily_order_id;
        $orderPaymentStatus = $order->payment_status;
        $orderStatus = ucwords($order->order_status);

        $orderedItems = Order_Item::join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')
            ->where('order_items.order_id', $order_id)
            ->orderByDesc('order_items.updated_at')
            ->get();

        return response()->json([
            'order_id' => $order_id,
            'daily_order_id' => $dailyOrderId,
            'order_status' => $orderStatus,
            'payment_status' => $orderPaymentStatus,
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

            $orderedItems = Order_Item::where('order_id', $order_id)->where('status', 'in queue')->get();

            $order->order_status = "preparing";

            if ($order->save()) {

                foreach ($orderedItems as $item) {
                    $item->status = 'preparing';
                    $item->save();
                }

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

        $orderCount = Order::where('order_status', ['in queue', 'preparing'])->count('order_id');

        if ($order) {
            $orderedItems = Order_Item::where('order_id', $order_id)->where('status', 'preparing')->get();

            $order->order_status = "serving";

            if ($order->save()) {

                foreach ($orderedItems as $item) {
                    $item->status = 'serving';
                    $item->save();
                }
                $order_status = ucwords($order->order_status);

                $activity = "Order ID [" . $order_id . "] status is cooked and ready to serve.";
                $log->endLog($user_id, $user_type, $activity);

                return response()->json(['response' => true, 'order_status' => $order_status, 'order_count' => $orderCount]);
            } else {
                return response()->json(['response' => false]);
            }
        }
    }
}
