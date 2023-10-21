<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $table = "cashes";

    public $timestamps = false;

    protected $fillable = ['cash'];
}
