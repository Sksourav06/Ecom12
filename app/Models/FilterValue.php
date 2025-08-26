<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterValue extends Model
{
    // In your FilterValue model

    protected $fillable = ['value', 'filter_id', 'sort', 'status'];

    /**
     * Each FilterValue belongs to a Filter (e.g., Fabric, Sleeve).
     */
    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

    /**
     * Each FilterValue belongs to many Products.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_filter_values', 'filter_value_id', 'product_id');
    }

}
