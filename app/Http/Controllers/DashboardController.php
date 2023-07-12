<?php

namespace App\Http\Controllers;

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
}
