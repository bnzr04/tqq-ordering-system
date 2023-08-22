<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_Stock extends Model
{
    use HasFactory;

    protected $primaryKey = 'stock_id';

    protected $table = 'item_stocks';

    protected $fillable = [];
}
