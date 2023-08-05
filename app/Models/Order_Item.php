<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_item_id';

    protected $table = 'order_items';

    protected $fillable = [];
}