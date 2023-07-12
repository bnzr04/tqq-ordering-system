<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $casts = [
        'bindings' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'user_type',
        'activity',
        'query',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
