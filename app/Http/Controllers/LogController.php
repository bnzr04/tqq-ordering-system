<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
        return view('admin.logs.log'); // return the admin logs module
    }

    public function view(Request $request)
    {
        $thisDay = $request->input('this-day');
        $thisWeek = $request->input('this-week');
        $thisMonth = $request->input('this-month');

        $filter = $request->input('filter');

        if ($thisWeek) {
            $from = Carbon::now()->startOfWeek();
            $to = Carbon::now()->endOfWeek();
        } else if ($thisMonth) {
            $from = Carbon::now()->startOfMonth();
            $to = Carbon::now()->endOfMonth();
        } else if ($filter) {
            $from = $request->input('date-from');
            $to = $request->input('date-to');
            $to = date('Y-m-d', strtotime($to . ' +1 day'));
        } else {
            $from = Carbon::now()->startOfDay();
            $to = Carbon::now()->endOfDay();
        }

        // $from = Carbon::parse($from)->format('Y-m-d H:i:s');
        // $to = Carbon::parse($to)->format('Y-m-d H:i:s');

        $logs = Log::whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->get();

        foreach ($logs as $log) {
            $log->formatDate = Carbon::parse($log->created_at)->format('F j, Y, g:i:s A');
        }

        return response()->json($logs);
    }

    public function startLog()
    {
        DB::getQueryLog(); // enable the query log

        $user = Auth::user(); // store the user information
        $user_id = $user->id; // store the user id
        $user_type = $user->type; // store the user type

        return [$user_id, $user_type]; // return the user_id and user_type
    }

    public function endLog($user_id, $user_type, $activity) // get the parameters value
    {

        // Get the SQL query being executed
        $sql = DB::getQueryLog();

        if (is_array($sql) && count($sql) > 0) {
            $last_query = end($sql)['query']; // store the query if there's is query
        } else {
            $last_query = 'No query log found.'; // store this message if there's no query
        }

        Log::create([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'activity' => $activity,
            'query' => $last_query,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
