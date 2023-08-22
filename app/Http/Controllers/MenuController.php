<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() //this function will return the menu view depends on the user type
    {
        $user = auth()->user();
        if ($user->type == 'admin') {
            return view('admin.menu.menu');
        } else {
            return view('cashier.menu.menu');
        }
    }

    public function saveItem(Request $request) // this function will save the new item or on menu
    {
        $item_name = $request->input('item_name');
        $item_description = $request->input('item_description');
        $item_category = $request->input('item_category');
        $item_price = $request->input('item_price');

        $log = new LogController();

        list($user_id, $user_type) = $log->startLog();

        $menu = new Menu();
        $menu->name = ucwords($item_name);
        $menu->description = $item_description;
        $menu->category = ucwords($item_category);
        $menu->price = $item_price;

        if ($menu->save()) {
            $menuId = $menu->id;
            $activity = "New Item added to menu. MENU ID: " . $menuId;
            $log->endLog($user_id, $user_type, $activity);

            return true;
        } else {
            return false;
        }
    }

    public function fetchItem(Request $request) //this function will retrieve all the menu and the categories availble
    {
        $menu = Menu::orderBy('name')->get();

        $category = Menu::distinct('category')->pluck('category');

        return response()->json(['menu' => $menu, 'category' => $category]);
    }

    public function filterItemByCategory(Request $request) //this function will retrieve the items by category
    {
        $category = $request->input('category_value');

        if ($category) {
            $data = Menu::where('category', $category)
                ->orderBy('name');
        } else {
            $data =
                Menu::orderBy('name');
        }

        $data = $data->get();

        return response()->json($data);
    }

    public function searchItemByName(Request $request) //this function will retrieve the items by category
    {
        $searchValue = $request->input('item_name');

        $data = Menu::where('name', 'LIKE', '%' . $searchValue . '%')->get();

        return response()->json($data);
    }
}
