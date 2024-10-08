<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'mst_item_color';
    protected $fillable = [
        'color_name',
        'color_code',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
