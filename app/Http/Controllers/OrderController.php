<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index() //this function will return the orders view of the admin
    {
        return view('admin.orders.orders');
    }

    public function makeOrder() //this function will return the make-order view with the last id inserted in the table ($lastIdInTable)
    {

        return view('admin.orders.sub-page.make-order');
    }

    public function nextOrderId()
    {
        $lastOrder = Order::orderByDesc('order_id')->first();

        if (!$lastOrder) {
            $id = 1;
        } else {
            $id = $lastOrder->order_id + 1;
        }

        return response()->json($id);
    }

    public function submitOrder(Request $request) //this function will submit and save the order to database
    {
        $order_id = $request->input('order_id');
        $order_type = $request->input('order_type'); //store the selected order_type, (dine-in/take-out)
        $payment_status = $request->input('payment_status'); //store the selected payment_status, (pain/unpaid)
        $table_number = $request->input('table_number'); //store the input value of table_number from ajax
        $totalBill = $request->input('total_bill'); //store the total bill of the order
        $items = $request->input('order_items'); //store the items from the order_items array

        $cashier_id = auth()->user()->id; //store the id of the cashier 

        $logController = new LogController(); //initialize log controller

        list($user_id, $user_type) = $logController->startLog();

        $order = new Order(); //initialize the orders table
        $order->table_number = $table_number; //set the table number
        $order->cashier_id = $cashier_id; //set the cashier_id to the user submit the order 
        $order->order_type = $order_type; //set the order_type
        $order->payment_status = $payment_status; //set payment_status
        $order->total_amount = $totalBill; //set the total_amount by the value of $totalBill
        $order->order_status = 'in queue'; //set the order_status to 'in queue'
        $order->order_time = now();

        if ($order->save()) { //if the order is true means it successfully stored to database
            $activity = "Created new order. ORDER ID: " . $order_id;
            $logController->endLog($user_id, $user_type, $activity);

            foreach ($items as $item) {
                $orderItem = new Order_Item();
                $orderItem->order_id = $order_id;
                $orderItem->menu_item_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = intval($item['price']);
                $orderItem->save();

                $activity = "ITEM ID [" . $item['id'] . "] added to ORDER ID [" . $order_id . "].";
                $logController->endLog($user_id, $user_type, $activity);
            }

            return [
                'response' => true,
                'orderId' => $order_id,
            ];
        } else {
            return [
                'response' => false
            ];
        }
    }

    public function fetchOrders(Request $request) //this function will retrieve all the orders
    {
        $inQueue = $request->input('in-queue');
        $preparing = $request->input('preparing');
        $nowServing = $request->input('now-serving');
        $completed = $request->input('completed');
        $canceled = $request->input('canceled');

        $orders = Order::query();

        if ($inQueue) {
            $orders = $orders->where('order_status', 'in queue');
        } else if ($preparing) {
            $orders = $orders->where('order_status', 'preparing');
        } else if ($nowServing) {
            $orders = $orders->where('order_status', 'now serving');
        } else if ($completed) {
            $orders = $orders->where('order_status', 'completed');
        } else if ($canceled) {
            $orders = $orders->where('order_status', 'canceled');
        }


        $orders = $orders->orderByDesc('order_time')
            ->get();

        // foreach ($orders as $order) {
        //     $order->formatDate = Carbon::parse($order->order_time)->format('F j, Y, g:i:s A');
        // }

        return response()->json($orders);
    }
}
