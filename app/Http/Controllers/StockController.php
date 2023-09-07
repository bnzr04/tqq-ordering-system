<?php

namespace App\Http\Controllers;

use App\Models\Item_Stock;
use App\Models\Menu;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index() //this function will return the stocks view of admin
    {
        return view('admin.stocks.stocks');
    }

    public function fetchItemsAndStocks(Request $request)
    {
        $itemAndStock = Item_Stock::rightjoin('menu_items', 'item_stocks.menu_item_id', '=', 'menu_items.item_id')
            ->orderBy('menu_items.name')
            ->get();

        return response()->json($itemAndStock);
    }

    public function addOrRemoveStockQuantity(Request $request)
    {
        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $itemId = $request->input('item_id_value');
        $quantity = $request->input('quantity_value');
        $operation = $request->input('operation_value');

        $itemStockExist = Item_Stock::join('menu_items', 'item_stocks.menu_item_id', '=', 'menu_items.item_id')
            ->where('menu_item_id', $itemId)
            ->first();

        if ($itemStockExist) {
            $currentStockQuantity = $itemStockExist->quantity;

            if ($operation == "add") {
                $newStockQuantity = $currentStockQuantity + $quantity;
                $activity = $itemStockExist->name . " / " . $itemStockExist->category . " added [" . $quantity . "]. New Quantity stock [" . $newStockQuantity . "].";
            } else {
                if ($quantity > $currentStockQuantity) {
                    $response = "quantity is greater than";
                    return response()->json($response);
                } else {
                    $newStockQuantity = $currentStockQuantity - $quantity;
                    $activity = $itemStockExist->name . " / " . $itemStockExist->category . " deducted [" . $quantity . "]. New Quantity stock [" . $newStockQuantity . "].";
                }
            }

            $itemStockExist->quantity = $newStockQuantity;

            if ($itemStockExist->save()) {
                $log->endLog($user_id, $user_type, $activity);
                $response = true;
            } else {
                $response = false;
            }
        } else {
            if ($operation == "remove") {
                $response = "no stock";
            } else {
                $itemInfo = Menu::find($itemId);

                $newStock = new Item_Stock();
                $newStock->menu_item_id = $itemId;
                $newStock->quantity = $quantity;
                if ($newStock->save()) {
                    $activity = "Stock created for " . $itemInfo->name . " / " . $itemInfo->category . ". New Stock [" . $quantity . "].";
                    $log->endLog($user_id, $user_type, $activity);
                    $response = true;
                }
            }
        }

        return response()->json($response);
    }
}
