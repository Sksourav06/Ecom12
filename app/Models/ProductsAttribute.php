<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsAttribute extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'size',
        'price',
        'stock',
        'sort',
        'status',
        // ... অন্যান্য ফিল্ড যদি থাকে
    ];
}
