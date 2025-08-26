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
                                $isEdit = isset($brand) && !empty($brand->id);
                            @endphp

                            <form name="brandForm" id="brandForm"
                                action="{{ isset($brand) ? route('brand.update', $brand->id) : route('brand.store') }}"
                                method="post" enctype="multipart/form-data">

                                @csrf
                                @if (isset($brand))
                                    @method('PUT')
                                @endif

                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Brand Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter Name" value="{{ old('name', $brand->name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/*">
                                        @if (!empty($brand->image))
                                            <div class="mt-2" id="categoryImageBlock">
                                                <img src="{{ asset('front/images/brands/' . $brand->image) }}"
                                                    width="50" alt="Category Image">
                                                <a href="javascript:void(0);" class="text-danger deleteCategoryImage"
                                                    data-brand-id="{{ $brand->id }}">Delete</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="logo" class="form-label"> Brabd logo</label>
                                        <input type="file" class="form-control" id="logo" name="logo"
                                            accept="image/*">
                                        @if (!empty($brand->logo))
                                            <div class="mt-2">
                                                <img src="{{ asset('front/images/logos/' . $brand->logo) }}" width="50"
                                                    alt="logo">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="brand_discount" class="form-label"> Discount</label>
                                        <input type="text" class="form-control" id="brand_discount"
                                            placeholder="Enter   Discount" name="brand_discount"
                                            value="{{ old('brand_discount', $brand->discount ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="text" class="form-control" id="url" placeholder="Enter  URL "
                                            name="url" value="{{ old('url', $brand->url ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" rows="3" id="description" placeholder="Enter Catrgory Description "
                                            name="description" value="{{ old('description', $brand->description ?? '') }}"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title"
                                            placeholder="Enter Category Mata Title " name="meta_title"
                                            value="{{ old('meta_title', $brand->meta_title ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <input type="text" class="form-control" id="meta_description"
                                            placeholder="Enter Category Mata Description " name="meta_description"
                                            value="{{ old('meta_description', $brand->meta_description ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords"
                                            placeholder="Enter Category Mata Keywords " name="meta_keywords"
                                            value="{{ old('meta_keywords', $brand->meta_keywords ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="menu_status">Show on Header Menu</label>
                                        <input type="checkbox" name="menu_status"
                                            value="1"{{ !empty($brand->menu_status) ? 'checked' : '' }}>
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
