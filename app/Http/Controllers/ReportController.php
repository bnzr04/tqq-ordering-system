<?php

namespace App\Http\Controllers;

use App\Exports\ItemStocksExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function itemStockReportExport(Request $request)
    {
        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $date = $request->input("date");

        $filename = "TQQ_Item_Stock_" . Carbon::now()->format("Ymd-His") . ".xlsx";

        $response = Excel::download(new ItemStocksExport($filename, $date), $filename, \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);

        $activity = "Item Stock Report Downloaded.";
        $log->endLog($user_id, $user_type, $activity);

        return $response;
    }
}
