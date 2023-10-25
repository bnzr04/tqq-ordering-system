<?php

namespace App\Exports;

use App\Models\Cash;
use App\Models\Order;
use App\Models\Order_Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $date;
    private $cash;
    private $dineIn;
    private $takeOut;
    private $totalSales;
    private $takeHome;

    public function __construct($date)
    {
        $this->date = $date;

        if ($this->date !== null) {
            $start = Carbon::parse($this->date)->startOfDay()->format("Y-m-d H:i:s");
            $end = Carbon::parse($this->date)->endOfDay()->format("Y-m-d H:i:s");
        } else {
            $start = Carbon::now()->startOfDay()->format("Y-m-d H:i:s");
            $end = Carbon::now()->endOfDay()->format("Y-m-d H:i:s");
        }

        $date = Carbon::parse($start)->format('Y-m-d');

        $cashValue = Cash::whereRaw("DATE(date) = ?", [$date])->value("cash");

        $dineInValue = Order::where('order_type', 'dine-in')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->sum("total_amount");

        $takeOutValue = Order::where('order_type', 'take-out')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->sum("total_amount");

        $totalSalesValue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->sum("total_amount");

        $this->cash = number_format($cashValue);
        $this->dineIn = number_format($dineInValue);
        $this->takeOut = number_format($takeOutValue);
        $this->totalSales = number_format($totalSalesValue + $cashValue);
        $this->takeHome = number_format($totalSalesValue);
    }

    public function collection()
    {

        if ($this->date !== null) {
            $start = Carbon::parse($this->date)->startOfDay()->format("Y-m-d H:i:s");
            $end = Carbon::parse($this->date)->endOfDay()->format("Y-m-d H:i:s");
        } else {
            $start = Carbon::now()->startOfDay()->format("Y-m-d H:i:s");
            $end = Carbon::now()->endOfDay()->format("Y-m-d H:i:s");
        }

        $date = Carbon::parse($start)->format('Y-m-d');

        $soldItems = Order_Item::select('menu_items.name', 'menu_items.category', 'menu_items.description', 'menu_items.price', DB::raw('SUM(order_items.quantity) as sold_quantity'), DB::raw('menu_items.price * SUM(order_items.quantity) as subtotal'))
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.item_id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('menu_items.name', 'menu_items.category', 'menu_items.description', 'menu_items.price')
            ->whereBetween('order_items.created_at', [$start, $end])
            ->get();

        return $soldItems;
    }

    public function headings(): array
    {
        if ($this->date !== null) {
            $date = Carbon::parse($this->date)->format("M d, Y");
        } else {
            $date = Carbon::now()->format("M d, Y");
        }

        return [
            ["SALES REPORT"],
            [
                "DATE : " . $date
            ],
            [],
            ["CASH:", "P " . $this->cash],
            ["DINE-IN:", "P " . $this->dineIn],
            ["TAKE-OUT:", "P " . $this->takeOut],
            ["TOTAL SALES:", "P " . $this->totalSales],
            ["TAKE HOME:", "P " . $this->takeHome],
            [],
            [
                "ITEM",
                "DESCRIPTION",
                "CATEGORY",
                "PRICE",
                "SOLD",
                "SUBTOTAL"
            ]
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->description,
            $item->category,
            number_format($item->price, 2, '.'),
            $item->sold_quantity,
            $item->subtotal,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true); //title
        $sheet->getStyle('A4:A8')->getFont()->setBold(true); //sales
        $sheet->getStyle('A10:F10')->getFont()->setBold(true); //header

        // Center align all cells from column A-F
        $sheet->getStyle('A:F')->getAlignment()->setHorizontal('center')->setVertical('center');
    }
}
