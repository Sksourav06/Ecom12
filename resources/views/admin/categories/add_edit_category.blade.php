@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Categories Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-4">
                    <div class="col-md-6">
                        <!-- Fixed the wrong '<' symbol here -->
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">{{ $title }}</div>
                            </div>

                            {{-- Alert messages --}}
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <strong>Error: </strong> {{ Session::get('error_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                    <strong>Success: </strong> {{ Session::get('success_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <strong>Error:</strong> {!! $error !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endforeach

                            {{-- Form Start --}}
                            @php
                                // Check if it's edit mode
                                $isEdit = isset($category) && !empty($category->id);
                            @endphp

                            <form name="categoryForm" id="categoryForm"
                                action="{{ $isEdit ? route('categories.update', $category->id) : route('categories.store') }}"
                                method="post" enctype="multipart/form-data">

                                @csrf
                                @if ($isEdit)
                                    @method('PUT')
                                @endif

                                <div class="card-body">

                                    {{-- Category Level Dropdown --}}
                                    <div class="mb-3">
                                        <label for="category_name">Category Level (Parent Category)*</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="">Select</option>
                                            <option value="" @if ($isEdit && $category->parent_id === null) selected @endif>
                                                Main Category
                                            </option>

                                            @foreach ($getCategories as $cat)
                                                <option value="{{ $cat['id'] }}"
                                                    @if ($isEdit && $category->parent_id == $cat['id']) selected @endif>
                                                    {{ $cat['name'] }}
                                                </option>

                                                @if (!empty($cat['subcategories']))
                                                    @foreach ($cat['subcategories'] as $subcat)
                                                        <option value="{{ $subcat['id'] }}"
                                                            @if ($isEdit && $category->parent_id == $subcat['id']) selected @endif>
                                                            &nbsp;&nbsp;&nbsp;&raquo; {{ $subcat['name'] }}
                                                        </option>

                                                        @if (!empty($subcat['subcategories']))
                                                            @foreach ($subcat['subcategories'] as $subsubcat)
                                                                <option value="{{ $subsubcat['id'] }}"
                                                                    @if ($isEdit && $category->parent_id == $subsubcat['id']) selected @endif>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;
                                                                    {{ $subsubcat['name'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_name" class="form-label">Category Name</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name"
                                            placeholder="Enter Category Name"
                                            value="{{ old('category_name', $category->name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_image" class="form-label">Category Image</label>
                                        <input type="file" class="form-control" id="category_image" name="category_image"
                                            accept="image/*">
                                        @if (!empty($category->image))
                                            <div class="mt-2" id="categoryImageBlock">
                                                <img src="{{ asset('front/images/categories/' . $category->image) }}"
                                                    width="50" alt="Category Image">
                                                <a href="javascript:void(0);" class="text-danger deleteCategoryImage"
                                                    data-category-id="{{ $category->id }}">Delete</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="size_chart" class="form-label">Size Chart</label>
                                        <input type="file" class="form-control" id="size_chart" name="size_chart"
                                            accept="image/*">
                                        @if (!empty($category->image))
                                            <div class="mt-2">
                                                <img src="{{ asset('front/images/sizechart/' . $category->size_chart) }}"
                                                    width="50" alt="Category Image">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_discount" class="form-label">Category Discount</label>
                                        <input type="text" class="form-control" id="category_discount"
                                            placeholder="Enter  Category Discount" name="category_discount"
                                            value="{{ old('category_discount', $category->discount ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="text" class="form-control" id="url"
                                            placeholder="Enter  URL " name="url"
                                            value="{{ old('url', $category->url ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" rows="3" id="description" placeholder="Enter Catrgory Description "
                                            name="description" value="{{ old('description', $category->description ?? '') }}"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title"
                                            placeholder="Enter Category Mata Title " name="meta_title"
                                            value="{{ old('meta_title', $category->meta_title ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <input type="text" class="form-control" id="meta_description"
                                            placeholder="Enter Category Mata Description " name="meta_description"
                                            value="{{ old('meta_description', $category->meta_description ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords"
                                            placeholder="Enter Category Mata Keywords " name="meta_keywords"
                                            value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="menu_status">Show on Header Menu</label>
                                        <input type="checkbox" name="menu_status"
                                            value="1"{{ !empty($category->menu_status) ? 'checked' : '' }}>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                            {{-- Form End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
