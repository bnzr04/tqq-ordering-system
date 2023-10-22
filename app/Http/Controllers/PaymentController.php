<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //this function will return the payment view
    public function index(Request $request)
    {
        return view('admin.payment.payment');
    }

    public function fetchUnpaidOrders(Request $request)
    {
        $orders = Order::where('payment_status', 'unpaid')->orderByDesc('order_time')->get();

        return response()->json($orders);
    }

    public function markOrderAsPaid(Request $request)
    {
        $orderId = $request->input("order_id");

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $order = Order::find($orderId);
        $paymentStatus = $order->payment_status;

        if ($order && $paymentStatus == "unpaid") {
            $order->payment_status = "paid";
            if ($order->save()) {
                $activity = "Order ID [" . $orderId . "] is marked as paid.";
                $log->endLog($user_id, $user_type, $activity);

                $response = true;
            } else {
                $response = false;
            }
        }

        return response()->json($response);
    }
}
