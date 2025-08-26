<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id')->with('parentcategory');
    }

    public function product_images(){
        return $this->hasMany(ProductsImage::class);
    }
     public function attributes(){
        return $this->hasMany('App\Models\ProductsAttribute');
     }
     public function filtervalues(){
        return $this->belongsToMany(FilterValue::class,'product_filter_values','product_id','filter_value_id');
     }
}
