<?php

namespace App\Http\Controllers;

use App\Models\Item_Stock;
use App\Models\Menu;
use Illuminate\Http\Request;

class StockController extends Controller
{

    public function fetchItemsAndStocks(Request $request)
    {
        $itemAndStock = Item_Stock::rightjoin('menu_items', 'item_stocks.menu_item_id', '=', 'menu_items.item_id')
            ->orderBy('menu_items.name')
            ->get();

        return response()->json($itemAndStock);
    }

    public function createNewStockForItem($itemId, $quantity, $operation)
    {
        $item = Menu::find($itemId);
        $itemStock = new Item_Stock();

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        if ($operation == "add") {
            $itemStock->menu_item_id = $itemId;
            $itemStock->quantity = $quantity;
            if ($itemStock->save()) {
                $activity = "Stock created for " . $item->name . " / " . $item->category . ". New Stock [" . $quantity . "].";
                $log->endLog($user_id, $user_type, $activity);
                $response = true;
            } else {
                $response = false;
            }
        } else {
            $response = "no_stock";
        }

        return $response;
    }

    public function deleteZeroItemStock($itemId)
    {
        $itemExist = Item_Stock::where("menu_item_id", $itemId)->first();

        if ($itemExist) {
            $itemExist->delete();
            return true;
        }
    }

    public function addOrRemoveStockQuantity(Request $request)
    {
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity');
        $operation = $request->input('operation');

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $item = Menu::find($itemId);
        $itemIdExistInItemStock = Item_Stock::where("menu_item_id", $itemId)->first();

        if ($itemIdExistInItemStock) {
            $currentItemQuantity = $itemIdExistInItemStock->quantity;

            if ($operation == "add") {
                $newItemQuantity = $currentItemQuantity + $quantity;
                $activity = $item->name . " / " . $item->category . " added [" . $quantity . "]. New Quantity stock [" . $newItemQuantity . "].";
            } else if ($operation == "remove") {
                if ($quantity > $currentItemQuantity) {
                    $response = "insufficient";
                    return response()->json($response);
                } else {
                    $newItemQuantity = $currentItemQuantity - $quantity;

                    if ($newItemQuantity <= 0) {
                        $activity = $item->name . " / " . $item->category . " deducted [" . $quantity . "]. Zero quantity stock [" . $newItemQuantity . "].";
                    } else {
                        $activity = $item->name . " / " . $item->category . " deducted [" . $quantity . "]. New quantity stock [" . $newItemQuantity . "].";
                    }
                }
            }

            $itemIdExistInItemStock->quantity = $newItemQuantity;

            if ($itemIdExistInItemStock->save()) {
                $log->endLog($user_id, $user_type, $activity);
                $response = true;

                if ($itemIdExistInItemStock->quantity <= 0) {
                    $this->deleteZeroItemStock($itemId);
                }
            } else {
                $response = false;
            }
        } else {
            $response = $this->createNewStockForItem($itemId, $quantity, $operation);
        }

        return response()->json($response);
    }

    public function filterStockByRange(Request $request)
    {
        $range = $request->input("range");

        $items = Menu::leftJoin("item_stocks", "menu_items.item_id", "=", "item_stocks.menu_item_id")
            ->select("menu_items.*", "item_stocks.quantity")
            ->groupBy(
                "menu_items.item_id",
                "menu_items.name",
                "menu_items.description",
                "menu_items.category",
                "menu_items.price",
                "menu_items.max_level",
                "menu_items.warning_level",
                "menu_items.created_at",
                "menu_items.updated_at",
                "item_stocks.quantity",
            );

        switch ($range) {
            case "overmax":
                $items = $items->havingRaw("item_stocks.quantity > menu_items.max_level");
                break;
            case "safe":
                $items = $items->havingRaw("item_stocks.quantity <= menu_items.max_level AND item_stocks.quantity > (menu_items.max_level * (menu_items.warning_level / 100))");
                break;
            case "warning":
                $items = $items->havingRaw("item_stocks.quantity <= menu_items.max_level * (menu_items.warning_level / 100)");
                break;
            case "no_stock":
                $items = $items->whereNull("item_stocks.menu_item_id");
                break;
            default:
                $items = $items->whereNotNull("item_stocks.menu_item_id");
                break;
        }

        $items = $items->get();

        return response()->json($items);
    }
}
