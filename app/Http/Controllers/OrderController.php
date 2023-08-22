<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Order_Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function nextOrderId() //this function will return the next order id
    {
        $lastOrder = Order::orderByDesc('order_time')->first(); //get the last data inserted

        if (!$lastOrder) { //if the lasOrder is false means no data exist
            $daily_order_id = 1; //store value 1
        } else { //else the lastOrder is true
            $daily_order_id = $lastOrder->daily_order_id + 1; //fetch the daily_order_id of the lastOrder + 1 and store the value 
            $lastOrderDate = Carbon::parse($lastOrder->order_time)->format('Y-m-d'); //format the order_time value to YYYY-MM-DD
        }

        $today = Carbon::now()->format('Y-m-d'); //get the current date and format to YYYY-MM-DD

        if ($lastOrder != true) {
            $nextOrderId = $daily_order_id;
        } else {
            $nextOrderId = $lastOrder->order_id;
            $nextOrderId = $nextOrderId + 1;
        }

        if (!$lastOrder) {
            return response()->json(['daily_order_id' => $daily_order_id, 'next_order_id' => $nextOrderId, 'today_date' => $today]);
        } else {
            if ($today !== $lastOrderDate) {
                $daily_order_id = 1;
            }

            return response()->json(['daily_order_id' => $daily_order_id, 'next_order_id' => $nextOrderId]);
        }
    }

    public function submitOrder(Request $request) //this function will submit and save the order to database
    {
        $order_id = $request->input('order_id'); //store the value of order_id
        $daily_order_id = $request->input('daily_order_id'); //store the value of daily_order_id
        $order_type = $request->input('order_type'); //store the selected order_type, (dine-in/take-out)
        $payment_status = $request->input('payment_status'); //store the selected payment_status, (pain/unpaid)
        $table_number = $request->input('table_number'); //store the input value of table_number from ajax
        $totalBill = $request->input('total_bill'); //store the total bill of the order
        $items = $request->input('order_items'); //store the items from the order_items array

        $cashier_id = auth()->user()->id; //store the id of the cashier 

        $logController = new LogController(); //initialize log controller

        list($user_id, $user_type) = $logController->startLog();

        $order = new Order(); //initialize the orders table
        $order->daily_order_id = $daily_order_id;
        $order->table_number = $table_number; //set the table number
        $order->cashier_id = $cashier_id; //set the cashier_id to the user submit the order 
        $order->order_type = $order_type; //set the order_type
        $order->payment_status = $payment_status; //set payment_status
        $order->total_amount = $totalBill; //set the total_amount by the value of $totalBill
        $order->order_status = 'in queue'; //set the order_status to 'in queue'
        $order->order_time = now();

        if ($order->save()) { //if the order is true means it successfully stored to database
            $activity = "Created new order. ORDER ID [" . $order_id . "]."; //store the activity message
            $logController->endLog($user_id, $user_type, $activity); //pass the value of activity to the endLog function of the log controller 

            //every item of the order will save it to the order_items table
            foreach ($items as $item) { //each of the item
                $itemInfo = Menu::find($item['id']); //get item id info from menu table

                $orderItem = new Order_Item(); //initiate order_items table by the Order_item model
                $orderItem->order_id = $order->order_id;
                $orderItem->menu_item_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = intval($item['price']);
                if ($orderItem->save()) { //if the item is saved
                    $orderItemResponse = true; //set as true
                } else {
                    $orderItemResponse = false; //set as false
                }

                $activity = "[" . $item['quantity'] . "] " . $itemInfo->name . " / " . $itemInfo->category . " is added to ORDER ID [" . $order_id . "]."; //activity message for every item
                $logController->endLog($user_id, $user_type, $activity); //save the activity to the logs table
            }

            return [
                'response' => true,
                'orderId' => $order_id,
                'orderItemIsSaved' => $orderItemResponse,
            ];
        } else {
            return [
                'response' => false
            ];
        }
    }

    public function fetchOrders(Request $request) //this function will retrieve all the orders
    {
        $inQueue = $request->input('in-queue'); //get the boolean value of the in-queue
        $preparing = $request->input('preparing'); //get the boolean value of the preparing
        $nowServing = $request->input('now-serving'); //get the boolean value of the now-serving
        $completed = $request->input('completed'); //get the boolean value of the completed
        $canceled = $request->input('canceled'); //get the boolean value of the canceled

        $orders = Order::query(); //initialize query for orders table using Order model

        if ($inQueue) { // if the $inQueue value is true
            $orders = $orders->where('order_status', 'in queue'); //select row where order_status is 'in queue'
        } else if ($preparing) { // if the $preparing value is true
            $orders = $orders->where('order_status', 'preparing'); //select row where order_status is 'preparing'
        } else if ($nowServing) { // if the $nowServing value is true
            $orders = $orders->where('order_status', 'serving'); //select row where order_status is 'serving'
        } else if ($completed) { // if the $completed value is true
            $orders = $orders->where('order_status', 'completed'); //select row where order_status is 'completed'
        } else if ($canceled) { // if the $canceled value is true
            $orders = $orders->where('order_status', 'canceled'); //select row where order_status is 'canceled'
        }

        $from = Carbon::now()->startOfDay(); //get the todays date and the starting time from 00:00:00 of the current date
        $to = Carbon::now()->endOfDay(); //get the todays date and the end time to 23:59:59 of the current date

        $orders = $orders->whereBetween('order_time', [$from, $to])->orderByDesc('order_time')
            ->get();

        foreach ($orders as $order) {
            $order->formatDate = Carbon::parse($order->order_time)->format('F j, Y, g:i:s A');
            $order->ordered_items = Order_Item::join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')
                ->where('order_id', $order->order_id)
                ->orderByDesc('order_items.created_at')
                ->get();
        }

        return response()->json($orders);
    }

    public function getOrderedItemsOfOrder(Request $request)
    {
        $order_id = $request->input('order_id');

        $orderedItems = Order_Item::join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')->where('order_id', $order_id)->get();

        return response()->json($orderedItems);
    }

    public function addNewItemToOrder(Request $request)
    {
        $log = new LogController();

        list($user_id, $user_type) = $log->startLog();

        $order_id = $request->input('order_id');
        $subtotal = $request->input('subtotal');
        $added_items_array = $request->input('added_items');

        $order = Order::find($order_id);

        if ($order) {
            $order_total_amount = $order->total_amount;
            $new_total_amount = intval($order_total_amount) + $subtotal;

            $order->order_status = 'in queue';
            $order->total_amount = $new_total_amount;
            $order->save();

            foreach ($added_items_array as $item) {

                $menuItem = Menu::where('item_id', $item['item_id'])
                    ->first();

                $order_item = new Order_Item();
                $order_item->order_id = $order_id;
                $order_item->menu_item_id = $item['item_id'];
                $order_item->quantity = $item['quantity'];
                $order_item->unit_price = $item['price'];
                $order_item->status = 'in queue';

                if ($order_item->save()) {
                    $activity = "[" . $item['quantity'] . "] " . $menuItem->name . " / " . $menuItem->category . " is added in order id [" . $order_id . "].";
                } else {
                    $activity = "[" . $item['quantity'] . "] " . $menuItem->name . " / " . $menuItem->category . " is failed to add in order id [" . $order_id . "].";
                }

                $log->endLog($user_id, $user_type, $activity);
            }

            return response()->json(['response' => true]);
        }
    }

    public function updateItemStatusToComplete(Request $request) //this function will update the item ordered status from 'serving' to 'completed'
    {
        $itemOrderId = $request->input('item_order_id'); //get the item order id value

        $item = Order_Item::find($itemOrderId); //store the item order id data from order_items table
        $item->status = 'completed'; //set the item status to 'completed'

        if ($item->save()) { //if successfully saved to database
            $response = true; //set response to true
        } else {
            $response = false; //else set response to false
        }

        return response()->json($response); //return response
    }

    public function removeItemQuantity(Request $request) //this function will remove a quantity to the ordered quantity if the item status is 'in queue'
    {
        $log = new LogController(); //initiate the log controller
        list($user_id, $user_type) = $log->startLog(); //initiate the startLog function of log controller

        $orderId = $request->input('order_id'); //get the order id value
        $itemOrderId = $request->input('item_order_id'); //get the item order id value
        $removeQuantity = $request->input('remove_quantity'); //get the item remove_quantity value

        $item = Order_Item::find($itemOrderId); //store the item order id data from order_items table
        $itemInfo = Order_Item::join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')
            ->where('order_items.order_item_id', $itemOrderId)
            ->first();

        if ($item->status !== 'in queue') { //if the item status is not equal to 'in queue'
            $status = false; //set status variable to false
            return response()->json(['in_queue_status' => $status]); //return response status as false
        }

        $itemQuantity = $item->quantity; //store the item quantity

        $remainingQuantity = $itemQuantity - $removeQuantity; //subtract the value of $removeQuantity value to the value of $itemQuantity and store the answer

        if ($remainingQuantity < 1) { //if the $remainingQuantity value is less than 1
            if ($item->delete()) { // if the item deleted successfully
                $activity = "[" . $removeQuantity . "] " . $itemInfo->name . " / " . $itemInfo->category . " is removed to order id [" . $orderId . "].";
                $log->endLog($user_id, $user_type, $activity);
            }
        } else {
            $item->quantity = $remainingQuantity; //else store the value of $remainingQuantity to the item quantity

            if ($item->save()) { // if the item successfully updated the data
                $activity = "[" . $removeQuantity . "] " . $itemInfo->name . " / " . $itemInfo->category . " is removed in order id [" . $orderId . "].";
                $log->endLog($user_id, $user_type, $activity);
            }
        }

        $itemPrice = $item->unit_price; //store the item unit price

        $deductedAmount = $itemPrice * $removeQuantity; //multiply the value of $itemPrice to the $removeQuantity and store the answer

        $order = Order::find($orderId); //find the order id

        $checkOrderItems = Order_Item::where('order_id', $orderId)->get(); //get all the item where the order_id is equal to the $orderId value

        if (count($checkOrderItems) < 1) { //if the item count of the order is less than 1 means 0
            if ($order->delete()) { // if the order is deleted successfully
                $activity = "Order id [" . $orderId . "] has 0 items and successfully deleted.";
                $log->endLog($user_id, $user_type, $activity);
            }
        } else {
            $orderAmount = $order->total_amount; //store the value of the order total amount
            $order->total_amount = $orderAmount - $deductedAmount; //subtract the value of $deductedAmount to the $orderAmount and store the answer to order total amount
            $order->save(); //update the data
        }

        $status = true; //set response to true

        return response()->json(['in_queue_status' => $status]); //return response as true
    }
}
