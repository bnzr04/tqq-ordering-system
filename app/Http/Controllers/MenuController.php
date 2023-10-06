<?php

namespace App\Http\Controllers;

use App\Models\Item_Stock;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() //redirect to either admin or cashier menu blade
    {
        $user = auth()->user();
        if ($user->type == 'admin') {
            return view('admin.menu.menu');
        } else {
            return view('cashier.menu.menu');
        }
    }

    public function saveItem(Request $request) //save new item to menu_items table
    {
        $item_name = $request->input('item_name');
        $item_description = $request->input('item_description');
        $item_category = $request->input('item_category');
        $item_price = $request->input('item_price');
        $max_quantity = $request->input('max_quantity');
        $warning_level = $request->input('warning_level');

        $log = new LogController();

        list($user_id, $user_type) = $log->startLog();

        $menu = new Menu();
        $menu->name = ucwords($item_name);
        $menu->description = $item_description;
        $menu->category = ucwords($item_category);
        $menu->price = $item_price;
        $menu->max_level = $max_quantity;
        $menu->warning_level = $warning_level;

        if ($menu->save()) {
            $menuId = $menu->item_id;
            $itemName = $menu->name;
            $activity = "New Item added to menu. Name [" . $itemName . "] Menu ID [" . $menuId . "].";
            $log->endLog($user_id, $user_type, $activity);

            $response = true;
        } else {
            $response = false;
        }

        return response()->json($response);
    }

    public function fetchCategories(Request $request)
    {
        $category = Menu::distinct('category')->pluck('category');

        return response()->json($category);
    }

    public function fetchItems(Request $request) //retrieve all the items on menu and its stock quantity from item_stocks table and the categories of items
    {
        $menu = Menu::join("item_stocks", "menu_items.item_id", "=", "item_stocks.menu_item_id")->get();

        return response()->json($menu);
    }

    public function filterItemByCategory(Request $request) //retrieve all the items that matched on the category value
    {
        $category = $request->input('category');

        if ($category) {
            if ($category === "all") {
                $data = Menu::leftjoin('item_stocks', 'menu_items.item_id', '=', 'item_stocks.menu_item_id')
                    ->orderBy('menu_items.name');
            } else {
                $data = Menu::leftjoin('item_stocks', 'menu_items.item_id', '=', 'item_stocks.menu_item_id')
                    ->where('menu_items.category', $category)
                    ->orderBy('menu_items.name');
            }
        }

        $data = $data->get();

        return response()->json($data);
    }

    public function searchItemByName(Request $request) //retrieve item by the matching letter or word of the item name
    {
        $searchValue = $request->input('item_name');

        $data = Menu::leftjoin("item_stocks", "menu_items.item_id", "=", "item_stocks.menu_item_id")
            ->where('menu_items.name', 'LIKE', '%' . $searchValue . '%')
            ->get();

        return response()->json($data);
    }

    public function updateItemInformation(Request $request) //update the item information
    {
        $item_id = $request->input('item_id');
        $item_name = $request->input('item_name');
        $item_description = $request->input('item_description');
        $item_category = $request->input('item_category');
        $item_price = $request->input('item_price');

        $updateItemInfo = Menu::find($item_id);
        if ($item_category == "") {
            $item_category = $updateItemInfo->category;
        }

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $updateItemInfo->name = ucwords($item_name);
        $updateItemInfo->description = $item_description;
        $updateItemInfo->category = ucwords($item_category);
        $updateItemInfo->price = $item_price;

        if ($updateItemInfo->save()) {
            $activity = "Item ID [" . $item_id . "] information is updated.";
            $log->endLog($user_id, $user_type, $activity);
            $response = true;
        } else {
            $response = false;
        }

        return response()->json($response);
    }

    public function deleteItemInformation(Request $request) //delete item information in menu_items table
    {
        $itemId = $request->input("item_id");

        $itemExist = Menu::find($itemId);

        $itemName = $itemExist->name;

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        if ($itemExist) {
            $itemExist->delete();
            $activity = "Item ID [" . $itemId . "] " . $itemName . " is deleted to database.";
            $log->endLog($user_id, $user_type, $activity);
            $response = true;
        } else {
            $response = false;
        }

        return response()->json($response);
    }
}
