<?php
namespace App\Services\Front;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class indexService
{
    public function getHomePageBanners()
    {
        // Correct the spelling of 'silder' to 'slider'
        $homeSliderBanners = Banner::where('type', 'slider')
            ->where('status', 1)
            ->orderBy('sort', 'Desc')
            ->get()
            ->toArray();

        $homeFixBanners = Banner::where('type', 'Fix')
            ->where('status', 1)
            ->orderBy('sort', 'Desc')
            ->get()
            ->toArray();

        // Return the data using compact, which returns an array
        return compact('homeSliderBanners', 'homeFixBanners');
    }

    public function featuredProducts()
    {
        $featuredProducts = Product::select('id', 'category_id', 'product_name', 'product_price', 'discount_applied_on', 'product_discount', 'final_price', 'group_code', 'main_image')
            ->with(['product_images'])
            ->where(['is_featured' => 'Yes', 'status' => 1])
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(8)
            ->get()
            ->toArray();
        return compact('featuredProducts');
    }
    public function newArrivalProduct()
    {
        $newArrivalProduct = Product::select('id', 'category_id', 'product_name', 'product_price', 'discount_applied_on', 'product_discount', 'final_price', 'group_code', 'main_image')
            ->with(['product_images'])
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->latest()
            ->limit(8)
            ->get()
            ->toArray();
        return compact('newArrivalProduct');
    }
    public function homeCategories()
    {
        $categories = Category::select('id', 'name', 'url', 'image') // Ensure 'image' is selected
            ->whereNull('parent_id')
            ->where('status', 1)
            ->where('menu_status', 1)
            ->get()
            ->map(function ($category) {
                $allCategoryIds = $this->getAllcategoryIds($category->id);
                $productCount = Product::whereIn('category_id', $allCategoryIds)
                    ->where('status', 1)
                    ->where('stock', '>', 0)
                    ->count();

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image,
                    'url' => $category->url,
                    'product_count' => $productCount
                ];
            });

        return ['categories' => $categories->toArray()];
    }

    private function getAllcategoryIds($parentId)
    {
        $categoryId = [$parentId];

        $childIds = Category::where('parent_id', $parentId)
            ->where('status', 1)
            ->pluck('id'); // ✅ Add semicolon here

        foreach ($childIds as $childId) {
            $categoryId = array_merge($categoryId, $this->getAllcategoryIds($childId));
        }

        return $categoryId;
    }

}
