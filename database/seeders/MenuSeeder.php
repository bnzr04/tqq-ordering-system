<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name' => 'Lechon Kawali',
                'description' => 'w/rice - 1p',
                'category' => "Rice Toppings",
                'price' => 110,
            ],
            [
                'name' => 'Lechon Paksiw',
                'description' => 'w/rice - 1p',
                'category' => "Rice Toppings",
                'price' => 100,
            ],
            [
                'name' => 'Leg Quarter',
                'description' => 'w/rice - 1p',
                'category' => "Rice Toppings",
                'price' => 140,
            ],
            [
                'name' => 'Pork Adobo',
                'description' => 'w/rice - 1p',
                'category' => "Rice Toppings",
                'price' => 100,
            ],
            [
                'name' => 'Sweet & Sour Pork',
                'description' => 'w/rice - 1p',
                'category' => "Rice Toppings",
                'price' => 100,
            ],
            [
                'name' => 'Sze Chuan Beef Brisket',
                'description' => 'w/rice - 1p',
                'category' => "Rice Toppings",
                'price' => 120,
            ],
            [
                'name' => 'Ampalaya Con Beef',
                'description' => '2-3p',
                'category' => "Guisado",
                'price' => 180,
            ],
            [
                'name' => 'Chicken Mushroom',
                'description' => '4-5p',
                'category' => "Soup",
                'price' => 150,
            ],

        ];

        foreach ($menus as $key => $menu) {
            Menu::create($menu);
        }
    }
}
