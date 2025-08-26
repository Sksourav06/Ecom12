<?php use App\Models\ProductsFilter; ?>
<div class="col-lg-3 col-md-12">
    <!-- Price Start -->
    <div class="border-bottom mb-4 pb-4">
        <h5 class="font-weight-semi-bold mb-4">Filter by Price</h5>
        @php
        $prices = ['0-1000','1000-2000','2000-5000','5000-10000','10000-100000'];
        $selectedPrices = [];

        if(request()->has('price')) {
        $selectedPrices = explode('~', request()->get('price')); // returns array
        }
        @endphp
        <div>
            @foreach($prices as $key => $price)
            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                <input type="checkbox"
                       class="custom-control-input filterAjax"
                       id="price{{ $key }}"
                       name="price[]"
                       value="{{ $price }}"
                       {{ in_array($price, $selectedPrices) ? 'checked' : '' }}>
                <label class="custom-control-label" for="price{{ $key }}">${{ $price }}</label>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Price End -->

    <!-- Color Start -->
    <div class="border-bottom mb-4 pb-4">
        <h5 class="font-weight-semi-bold mb-4">Filter by Color</h5>

        @php

        // Get available colors for current category
        $getColors = ProductsFilter::getColors($catIds ?? []);

        // Get selected colors from the query string
        $selectedColors = request()->has('color')
        ? explode('~', request()->get('color'))
        : [];
        @endphp

        @if (!empty($getColors))
        <div>
            @foreach($getColors as $key => $color)
            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                <input
                    type="checkbox"
                    name="color"
                    class="custom-control-input filterAjax"
                    id="color{{ $key }}"
                    value="{{ $color }}"
                    {{ in_array($color, $selectedColors) ? 'checked' : '' }}>
                <label class="custom-control-label" for="color{{ $key }}">
                    {{ ucfirst($color) }}
                </label>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <!-- Color End -->

    <!-- Size Start -->
    <div class="mb-5">
        <h5 class="font-weight-semi-bold mb-4">Filter by Size</h5>
        @php
        $getSizes = ProductsFilter::getSizes($catIds);
        $selectedSizes = request()->has('size') ? explode('~', request()->get('size')) : [];
        @endphp
        <div>
            @foreach($getSizes as $key => $size)
            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                <input type="checkbox"
                       name="size"
                       id="size{{ $key }}"
                       value="{{ $size }}"
                       class="custom-control-input filterAjax"
                       {{ in_array($size, $selectedSizes) ? 'checked' : '' }}>
                <label class="custom-control-label" for="size{{ $key }}">{{ strtoupper($size) }}</label>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Size End -->

    <div class="border-bottom mb-4 pb-4">
        <h5 class="font-weight-semi-bold mb-4">Filter by Brand</h5>
        @php
        $getBrands = ProductsFilter::getBrands($catIds);
        $selectedBrands = request()->has('brand') ? explode('~', request()->get('brand')) : [];
        @endphp

        @if(!empty($getBrands))
            @foreach($getBrands as $key => $brand)
            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                <input type="checkbox"
                       name="brand[]"
                       id="brand{{ $key }}"
                       value="{{ $brand['name'] }}"
                       class="custom-control-input filterAjax"
                       {{ in_array($brand['name'], $selectedBrands) ? 'checked' : '' }}>
                <label class="custom-control-label" for="brand{{ $key }}">{{ ucfirst($brand['name']) }}</label>
            </div>
            @endforeach
        </div>
        @endif

    @foreach($filters as $filter)
    @php
    $filterValues = $filter->values->filter(function ($val) use ($catIds) {
    return $val->products->whereIn('category_id', $catIds)->isNotEmpty();
    });

    $selectedValues = request()->has($filter->filter_name)
    ? explode('~', request()->get($filter->filter_name))
    : [];
    @endphp

    @if($filterValues->isNotEmpty())
    <div class="border-bottom mb-4 pb-4">
        <h5 class="font-weight-semi-bold mb-4">Filter by {{ ucwords($filter->filter_name) }}</h5>
        <div>
            @foreach($filterValues as $key => $value)
            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                <input type="checkbox"
                       name="{{ $filter->filter_name }}[]"
                       id="{{ $filter->filter_name }}{{ $key }}"
                       value="{{ $value->value }}"
                       class="custom-control-input filterAjax"
                       {{ in_array($value->value, $selectedValues) ? 'checked' : '' }}>
                <label class="custom-control-label" for="{{ $filter->filter_name }}{{ $key }}">
                    {{ ucfirst($value->value) }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach
</div>

