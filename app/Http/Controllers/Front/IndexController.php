<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\Front\indexService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $indexService;
    public function __construct(IndexService $indexService)
    {
        $this->indexService = $indexService;
    }
    public function index()
    {
        $banners = $this->indexService->getHomePageBanners();
        $featured = $this->indexService->featuredProducts();
        $newArrivals = $this->indexService->newArrivalProduct();
        $categories = $this->indexService->homeCategories();
        return view('front.index')
            ->with($banners)
            ->with($featured)
            ->with($newArrivals)
            ->with($categories);
    }


}
