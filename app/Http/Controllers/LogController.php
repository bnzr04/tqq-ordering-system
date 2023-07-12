<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function startLog()
    {
        DB::getQueryLog(); // enable the query log

        $user = Auth::user(); // store the user information
        $user_id = $user->id; // store the user id
        $user_type = $user->type; // store the user type

        return [$user_id, $user_type];
    }

    public function endLog($user_id, $user_type, $activity)
    {

        // Get the SQL query being executed
        $sql = DB::getQueryLog();
        if (is_array($sql) && count($sql) > 0) {
            $last_query = end($sql)['query'];
        } else {
            $last_query = 'No query log found.';
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
