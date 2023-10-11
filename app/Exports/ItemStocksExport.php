<?php

namespace App\Exports;

use App\Models\Item_Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemStocksExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        $itemStock = Item_Stock::join("menu_items", "item_stocks.menu_item_id", "=", "menu_items.item_id")->get();

        return $itemStock;
    }

    public function headings(): array
    {
        return [
            ["ITEM STOCK REPORT"],
            [
                "As of: ",
                date('h:i A, F d, Y'),
            ],
            [],
            [
                "ITEM",
                "DESCRIPTION",
                "CATEGORY",
                "PRICE",
                "STOCK",
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
            $item->quantity,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true); //title
        $sheet->getStyle('A4:E4')->getFont()->setBold(true); //header

        // Center align all cells from column A-F
        $sheet->getStyle('A:F')->getAlignment()->setHorizontal('center')->setVertical('center');
    }
}
