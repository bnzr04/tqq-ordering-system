<?php

namespace App\Http\Controllers;

use App\Exports\ItemStocksExport;
use App\Exports\SalesExport;
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

        $download = Excel::download(new ItemStocksExport($filename, $date), $filename, \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);

        $activity = "Item Stock Report Downloaded.";
        $log->endLog($user_id, $user_type, $activity);

        return $download;
    }

    public function salesReportExport(Request $request)
    {
        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $date = $request->input("date");

        if ($date !== "") {
            $filename = "TQQ_Sales_" . Carbon::parse($date)->format("Ymd") . ".xlsx";
            $fileDate = Carbon::parse($date)->format("m/d/Y");
        } else {
            $filename = "TQQ_Sales_" . Carbon::now()->format("Ymd") . ".xlsx";
            $fileDate = Carbon::now()->format("m/d/Y");
        }

        $download = Excel::download(new SalesExport($date), $filename, \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);

        $activity = "Sales Report Downloaded [" . $fileDate . "]";
        $log->endLog($user_id, $user_type, $activity);

        return $download;
    }
}
