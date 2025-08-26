<?php /** @noinspection ALL */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductsFilter extends Model
{
    // ✅ Get Colors
    public static function getColors($catIds)
    {
        $getProductIds = Product::whereIn('category_id', $catIds)->pluck('id')->toArray();

        if (empty($getProductIds)) {
            return [];
        }

        $getProductColors = Product::whereIn('id', $getProductIds)
            ->whereNotNull('family_color')
            ->groupBy('family_color')
            ->pluck('family_color')
            ->toArray();

        return $getProductColors;
    }

    // ✅ Get Sizes
    public static function getSizes($catIds)
    {
        $getProductIds = Product::whereIn('category_id', $catIds)->pluck('id')->toArray();

        if (empty($getProductIds)) {
            return [];
        }

        $getProductSizes = ProductsAttribute::where('status', 1)
            ->whereIn('product_id', $getProductIds)
            ->whereNotNull('size')
            ->groupBy('size')
            ->pluck('size')
            ->toArray();

        return $getProductSizes;
    }

    // ✅ Get Brands
    public static function getBrands($catIds)
    {
        $getProductIds = Product::whereIn('category_id', $catIds)
            ->whereNotNull('category_id')
            ->pluck('id')
            ->toArray();

        if (empty($getProductIds)) {
            return [];
        }

        $getProductBrandIds = Product::whereIn('id', $getProductIds)
            ->whereNotNull('brand_id')
            ->groupBy('brand_id')
            ->pluck('brand_id')
            ->toArray();

        if (empty($getProductBrandIds)) {
            return [];
        }

        $brands = Brand::select('id', 'name')
            ->where('status', 1)
            ->whereIn('id', $getProductBrandIds)
            ->orderBy('name', 'ASC')
            ->get()
            ->toArray();

        return $brands;
    }


}
