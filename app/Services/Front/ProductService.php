<?php

namespace App\Services\Front;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Product;
use App\Models\ProductsAttribute;

class ProductService
{


    public function getCategoryListingData($url)
    {
        $categoryInfo = Category::categoryDetails($url);

        $query = Product::with(['product_images'])
            ->whereIn('category_id', $categoryInfo['catIds'])
            ->where('status', 1);
        $query = $this->applyFilters($query);
        $products = $query->paginate(6)->withQueryString();
        $filters = Filter::with(['values' => function ($q) {
            $q->where('status', 1)->orderBy('sort', 'asc');
        }])
            ->where('status', 1)
            ->orderBy('sort', 'asc')
            ->get();
        return [
            'categoryDetails' => $categoryInfo['categoryDetails'],
            'breadcrumbs' => $categoryInfo['breadcrumbs'],
            'categoryProducts' => $products,
            'selectedSort'=> request()->get('sort','latest' ),
            'url' => $url,
            'catIds'=>    $categoryInfo['catIds'],
            'filters'=> $filters,
        ];
    }

    private function applyFilters($query)
    {
        $sort = request()->get('sort');
        switch ($sort) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
                case 'low_to_high':
                    $query->orderBy('final_price', 'asc');
                    break;
                    case 'high_to_low':
                        $query->orderBy('final_price', 'desc');
                        break;
                        case 'best_sellers':
                            $query->inRandomOrder();
                            break;
                            case 'featured':
                                $query->where('is_featured', 'Yes')->orderBy('created_at', 'desc');
                                break;
                                case 'discounted':
                                    $query->where('product_discount','>',0);
                                    break;
                                default:
                                    $query->orderBy('created_at', 'desc');

        }
        if(request()->has('color') && !empty(request()->get('color'))){
            $color = array_filter(explode('~',request()->get('color')));
            if(count($color) > 0){
                $query->whereIn('family_color', $color);
            }
        }
       if (request()->has('size') && !empty(request()->get('size'))) {
       $sizes = explode('~', request()->get('size'));

        $getProductIds = ProductsAttribute::select('product_id')
            ->whereIn('size', $sizes) // <-- FIXED: match sizes, not product IDs
            ->where('status', 1)
            ->pluck('product_id')
            ->toArray();

        if (!empty($getProductIds)) {
            $query->whereIn('id', $getProductIds);
        }
    }
        if (request()->has('brand') && !empty(request()->get('brand'))) {
            $brands = array_map('trim', explode('~', request()->get('brand')));

            if (!empty($brands)) {
                $getBrandIds = Brand::whereIn('name', $brands)
                    ->pluck('id')
                    ->toArray();

                if (!empty($getBrandIds)) {
                    $query->whereIn('brand_id', $getBrandIds);
                }
            }
        }
        if (request()->has('price') && !empty(request()->get('price'))) {
            $priceRanges = explode('~', request()->get('price')); // ["0-1000", "5000-10000"]

            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    if (strpos($range, '-') !== false) {
                        [$min, $max] = explode('-', $range);
                        $q->orWhereBetween('final_price', [(int)$min, (int)$max]);
                    }
                }
            });

        }
        $filterParms = request()->all();

        foreach ($filterParms as $filterkey => $filtervalue) {
            if (in_array($filterkey, ['color', 'size','brand','price','sort','page','json'])) {
                continue;
            }

            $selectedvalues = explode('~', $filtervalue);

            if (!empty($selectedvalues)) {
                $query->whereHas('filterValues', function($q) use ($selectedvalues) {
                    $q->whereIn('value', $selectedvalues);
                });
            }
        }



        return $query;

    }
}
