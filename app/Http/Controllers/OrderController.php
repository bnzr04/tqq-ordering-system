<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.orders');
    }

    public function makeOrder()
    {
        return view('admin.orders.sub-page.make-order');
    }
}
