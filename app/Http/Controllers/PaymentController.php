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
}
