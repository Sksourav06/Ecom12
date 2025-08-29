@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Product Management</h3>
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
                                $isEdit = isset($product) && !empty($product->id);
                            @endphp

                            <form name="productForm" id="productForm"
                                action="{{ $isEdit ? route('product.update', $product->id) : route('product.store') }}"
                                method="post" enctype="multipart/form-data">

                                @csrf
                                @if ($isEdit)
                                    @method('PUT')
                                @endif

                                <div class="card-body">

                                    {{-- Category Level Dropdown --}}
                                    <div class="mb-3">
                                        <label for="category_id">Category Level (Select Category)*</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($getCategories as $cat)
                                                <option value="{{ $cat['id'] }}"
                                                    @if (old('category_id', $product->category_id ?? '') == $cat['id']) selected @endif>
                                                    {{ $cat['name'] }}
                                                </option>

                                                @if (!empty($cat['subcategories']))
                                                    @foreach ($cat['subcategories'] as $subcat)
                                                        <option value="{{ $subcat['id'] }}"
                                                            @if (old('category_id', $product->category_id ?? '') == $subcat['id']) selected @endif>
                                                            &nbsp;&nbsp;&nbsp;&raquo; {{ $subcat['name'] }}
                                                        </option>

                                                        @if (!empty($subcat['subcategories']))
                                                            @foreach ($subcat['subcategories'] as $subsubcat)
                                                                <option value="{{ $subsubcat['id'] }}"
                                                                    @if (old('category_id', $product->category_id ?? '') == $subsubcat['id']) selected @endif>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;
                                                                    {{ $subsubcat['name'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>

                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="brand_id" class="form-label">Select Brand</label>
                                        <select name="brand_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand['id'] }}"
                                                    {{ old('brand_id', $product['brand_id'] ?? '') == $brand['id'] ? 'selected' : '' }}>
                                                    {{ $brand['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name"
                                            placeholder="Enter Product Name"
                                            value="{{ old('product_name', $product->product_name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_code" class="form-label">Product Code</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code"
                                            placeholder="Enter Product Cde"
                                            value="{{ old('product_code', $product->product_code ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_color" class="form-label">Product Color</label>
                                        <input type="text" class="form-control" id="product_color" name="product_color"
                                            placeholder="Enter Product Color"
                                            value="{{ old('product_color', $product->product_color ?? '') }}">
                                    </div>
                                    <?php $familyColors = \App\Models\Color::colors(); ?>
                                    <div class="mb-3">
                                        <label for="group_code" class="form-label">Family Color</label>
                                        <select name="family_color" class="form-control">
                                            <option value="">Please Select</option>
                                            @foreach ($familyColors as $color)
                                                <option
                                                    value="{{ $color->name }}"@if (isset($product['family_color']) && $product['family_color'] == $color->name) selected @endif>
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="group_code" class="form-label">Group Code</label>
                                        <input type="text" class="form-control" id="group_code" name="group_code"
                                            placeholder="Enter Product Color"
                                            value="{{ old('group_color', $product->group_code ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_price" class="form-label">Product Price</label>
                                        <input type="text" class="form-control" id="product_price"
                                            name="product_price" placeholder="Enter Product Color"
                                            value="{{ old('product_price', $product->product_price ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_discount" class="form-label">Product Discount</label>
                                        <input type="number" step="0.01" class="form-control" id="product_discount"
                                            name="product_discount" placeholder="Enter Product Color"
                                            value="{{ old('product_discount', $product->product_discount ?? '') }}">
                                    </div>
                                    {{-- <div class="mb-3">
                                            <label for="product_discount_amount" class="form-label">Product Discount
                                                Aount</label>
                                            <input type="number" step="0.01" class="form-control"
                                                id="product_discount_amount" name="product_discount_amount"
                                                placeholder="Enter Product Color"
                                                value="{{ old('product_discount_amount', $product->product_discount_amount ?? '') }}">
                                        </div> --}}
                                    <div class="mb-3">
                                        <label for="product_gst" class="form-label">Product GST</label>
                                        <input type="number" step="0.01" class="form-control" id="product_gst"
                                            name="product_gst" placeholder="Enter Product Color"
                                            value="{{ old('product_gst', $product->product_gst ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_weight" class="form-label">Product Weight</label>
                                        <input type="number" step="0.01" class="form-control" id="product_weight"
                                            name="product_weight" placeholder="Enter Product Color"
                                            value="{{ old('product_weight', $product->product_weight ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mb-1">Product Attributes</label>

                                        {{-- Header Row --}}
                                        <div class="row fw-semibold bg-light mb-2 rounded border px-2 py-1">
                                            <div class="col-2">Size</div>
                                            <div class="col-2">SKU</div>
                                            <div class="col-2">Price</div>
                                            <div class="col-2">Stock</div>
                                            <div class="col-2">Sort</div>
                                            <div class="col-2">Action</div>
                                        </div>

                                        {{-- Dynamic Rows --}}
                                        <div class="field_wrapper">
                                            <div class="row g-2 attribute-row mb-2">
                                                <div class="col-2">
                                                    <input type="text" name="size[]" class="form-control"
                                                        placeholder="Size">
                                                </div>
                                                <div class="col-2">
                                                    <input type="text" name="sku[]" class="form-control"
                                                        placeholder="SKU">
                                                </div>
                                                <div class="col-2">
                                                    <input type="text" name="price[]" class="form-control"
                                                        placeholder="Price">
                                                </div>
                                                <div class="col-2">
                                                    <input type="text" name="stock[]" class="form-control"
                                                        placeholder="Stock">
                                                </div>
                                                <div class="col-2">
                                                    <input type="text" name="sort[]" class="form-control"
                                                        placeholder="Sort">
                                                </div>
                                                <div class="col-2 d-flex align-items-start">
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-success add_button mt-1">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (isset($product['attributes']) && count($product['attributes']) > 0)
                                        <div class="mb-3">
                                            <label class="form-label mb-1">Existing Product</label>
                                            <div class="table-responsive">
                                                <table class="table-bordered mb-0 table align-middle">
                                                    <thead class="table-light text-center">
                                                        <tr>
                                                            <th style="width: 15%;">Size</th>
                                                            <th style="width: 20%;">SKU</th>
                                                            <th style="width: 15%;">Price</th>
                                                            <th style="width: 15%;">Stock</th>
                                                            <th style="width: 15%;">Sort</th>
                                                            <th style="width: 15%;">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product['attributes'] as $attribute)
                                                            <tr class="text-center">
                                                                <input type="hidden" name="attrId[]"
                                                                    value="{{ $attribute['id'] }}">
                                                                <td>{{ $attribute['size'] }}</td>
                                                                <td>{{ $attribute['sku'] }}</td>
                                                                <td>
                                                                    <input type="number" name="update_price[]"
                                                                        value="{{ $attribute['price'] }}"
                                                                        class="form-control text-center" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="update_stock[]"
                                                                        value="{{ $attribute['stock'] }}"
                                                                        class="form-control text-center" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="update_sort[]"
                                                                        value="{{ $attribute['sort'] }}"
                                                                        class="form-control text-center" required>
                                                                </td>
                                                                <td>
                                                                    @if ($attribute['status'] == 1)
                                                                        <a class="updateAttributeStatus"
                                                                            id="attribute-{{ $attribute['id'] }}"
                                                                            attribute_id="{{ $attribute['id'] }}"
                                                                            style="color:#3f6ed3"
                                                                            href="javascript:void(0)">
                                                                            <i class="fas fa-toggle-on"
                                                                                data-status="Active"></i>
                                                                        </a>
                                                                    @else
                                                                        <a class="updateAttributeStatus"
                                                                            id="attribute-{{ $attribute['id'] }}"
                                                                            attribute_id="{{ $attribute['id'] }}"
                                                                            style="color:grey" href="javascript:void(0)">
                                                                            <i class="fas fa-toggle-off"
                                                                                data-status="Inactive"></i>
                                                                        </a>
                                                                    @endif
                                                                    <a title="Delete attribute" href="javascript:void(0)"
                                                                        class="confirmDlete text-danger"
                                                                        data-module="attribute"
                                                                        data-id="{{ $attribute['id'] }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Product Main Image --}}
                                    <div class="mb-3">
                                        <label for="main_image" class="form-label">Product
                                            Main
                                            Image</label>
                                        <div class="dropzone" id="mainImageDropzone">
                                        </div>

                                        @if (!empty($product['main_image']))
                                            <div style="margin-top: 10px;">
                                                <a target="_blank"
                                                    href="{{ url('front/images/product/' . $product['main_image']) }}">
                                                    <img style="width: 50px;"
                                                        src="{{ asset('front/images/product/' . $product['main_image']) }}"
                                                        alt="Product Image">
                                                </a>
                                                <a href="javascript:void(0)" class="confirmDelete"
                                                    title="Delete Product Image" data-module="product-main-image"
                                                    data-id="{{ $product['id'] }}">
                                                    <i class="fas fa-trash-alt"
                                                        style="color:#3f6ed3; margin-left:10px;"></i>
                                                </a>
                                            </div>
                                        @endif

                                        <input type="hidden" name="main_image_hidden" id="main_image_hidden">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_images_Dropzone" class="form-label">Alternate
                                            Product
                                            Images
                                            (Multiple Uploads)</label>
                                        <div class="dropzone" id="productimagesDropzone">
                                        </div>

                                        @if (isset($product->product_images) && $product->product_images->count() > 0)
                                            @foreach ($product->product_images as $img)
                                                <div style="display: inline-block; position: relative; margin: 5px;">
                                                    <a target="_blank"
                                                        href="{{ url('front/images/product/' . $img->image) }}">
                                                        <img src="{{ asset('front/images/product/' . $img->image) }}"
                                                            style="width: 100px;">
                                                    </a>
                                                    <a href="javascript:void(0)" class="confirmDelete"
                                                        data-module="product-image" data-id="{{ $img->id }}"
                                                        data-image="{{ $img->image }}">
                                                        <i class="fas fa-trash"
                                                            style="position: absolute; top: 0; right: 0; color: red;"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif

                                        <input type="hidden" name="product_images" id="product_images_hidden">
                                    </div>
                                    {{-- Product Video --}}
                                    <div class="mb-3">
                                        <label for="product_video" class="form-label">Product
                                            Video</label>
                                        <div id="productVideoDropzone" class="dropzone">
                                        </div>

                                        @if (!empty($product['product_video']))
                                            <div style="margin-top: 10px;">
                                                <a target="_blank"
                                                    href="{{ url('front/videos/product/' . $product['product_video']) }}">
                                                    <video width="150" controls>
                                                        <source
                                                            src="{{ asset('front/videos/product/' . $product['product_video']) }}"
                                                            type="video/mp4">
                                                        Your browser does not
                                                        support the video tag.
                                                    </video>
                                                </a>
                                            </div>
                                        @endif

                                        <input type="hidden" name="product_video_hidden" id="product_video_hidden">
                                    </div>

                                    <div class="mb-3">
                                        <label for="wash_care" class="form-label">Wash
                                            Care</label>
                                        <textarea class="form-control" name="wash_care" placeholder="Enter wash care instructions">{{ old('wash_care', $product->wash_care ?? '') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Enter product description">{{ old('description', $product->description ?? '') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="search_keywords" class="form-label">Search
                                            Keywords</label>
                                        <textarea class="form-control" id="search_keywords" name="search_keywords" placeholder="Enter search keywords">{{ old('search_keywords', $product->search_keywords ?? '') }}</textarea>
                                    </div>
                                    @php
                                        $filters = \App\Models\Filter::with(['values' => function ($q) {
                                            $q->where('status', 1)->orderBy('sort', 'asc');
                                        }])->where('status', 1)->orderBy('sort', 'asc')->get();

                                        $selectedValues = isset($product) ? $product->filterValues->pluck('id')->toArray() : [];
                                    @endphp

                                    @foreach($filters as $filter)
                                        <div class="mb-3">
                                            <label>{{ ucwords($filter->filter_name) }}</label>
                                            <select name="filter_values[{{ $filter->id }}]" class="form-control">
                                                <option value="">--Select {{ ucwords($filter->filter_name) }}--</option>
                                                @foreach($filter->values as $value)
                                                    <option value="{{ $value->id }}" {{ in_array($value->id, $selectedValues) ? 'selected' : '' }}>
                                                        {{ ucfirst($value->value) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta
                                            Title</label>
                                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                                            placeholder="Enter Meta Title"
                                            value="{{ old('meta_title', $product->meta_title ?? '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta
                                            Description</label>
                                        <input type="text" class="form-control" id="meta_description"
                                            name="meta_description" placeholder="Enter Meta Description"
                                            value="{{ old('meta_description', $product->meta_description ?? '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta
                                            Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords"
                                            name="meta_keywords" placeholder="Enter Meta Keywords"
                                            value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="is_featured" class="form-label">Is
                                            Featured</label>
                                        <select name="is_featured" class="form-select">
                                            <option value="No"
                                                {{ old('is_featured', $product->is_featured ?? '') == 'No' ? 'selected' : '' }}>
                                                No</option>
                                            <option value="Yes"
                                                {{ old('is_featured', $product->is_featured ?? '') == 'Yes' ? 'selected' : '' }}>
                                                Yes</option>
                                        </select>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        const mainImg = new Dropzone("#mainImageDropzone", {
            url: "{{ route('products.upload.image') }}",
            paramName: "file",
            maxFiles: 1,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            success(file, response) {
                document.getElementById('main_image_hidden').value = response.file_name;
            },
            error(file, err) {
                console.error("Upload error:", err);
            }
        });
    </script>
    <script>
        Dropzone.autoDiscover = false;

        const productVideoDropzone = new Dropzone("#productVideoDropzone", {
            url: "{{ route('products.upload.video') }}", // This route must exist
            paramName: "file",
            maxFiles: 1,
            acceptedFiles: ".mp4,.avi,.mov,.wmv,.mkv",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                document.getElementById('product_video_hidden').value = response.file_name;
            },
            error: function(file, response) {
                alert(response.message || "Upload failed.");
            },
            maxfilesexceeded: function(file) {
                this.removeAllFiles();
                this.addFile(file);
            }
        });
    </script>
    <script>
        Dropzone.autoDiscover = false;

        let uploadedImages = [];

        const myDropzone = new Dropzone("#productimagesDropzone", {
            url: "{{ route('products.upload.images') }}",
            paramName: "file",
            maxFilesize: 2, // MB
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },

            success: function(file, response) {
                file.serverFileName = response.image;
                uploadedImages.push(response.image);
                $('#product_images_hidden').val(JSON.stringify(uploadedImages));
            },

            removedfile: function(file) {
                const imageName = file.serverFileName;
                if (!imageName) return;

                $.ajax({
                    url: "{{ route('products.delete.image') }}",
                    type: "POST",
                    data: {
                        image: imageName,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        uploadedImages = uploadedImages.filter(img => img !== imageName);
                        $('#product_images_hidden').val(JSON.stringify(uploadedImages));
                    }
                });

                if (file.previewElement) {
                    file.previewElement.remove();
                }
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $(document).on("click", ".confirmDelete", function () {
                const module = $(this).data('module');
                const id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this image?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('products.delete.main.image') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire(
                                        "Deleted!",
                                        response.message,
                                        "success"
                                    ).then(() => {
                                        location
                                            .reload(); // অথবা শুধু image tag remove করতে পারো
                                    });
                                } else {
                                    Swal.fire("Error", response.message, "error");
                                }
                            },
                            error: function () {
                                Swal.fire("Error", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>
        const maxField = 10;
        const wrapper = $('.field_wrapper');
        const addButtonClass = '.add_button';
        const removeButtonHtml = `
        <a href="javascript:void(0);" class="btn btn-sm btn-danger remove_button" title="Remove row">
            <i class="fas fa-minus"></i>
        </a>
    `;

        // Add new row
        $(document).on('click', addButtonClass, function (e) {
            e.preventDefault();

            if (wrapper.find('.attribute-row').length >= maxField) return;

            const row = $(this).closest('.attribute-row').clone();
            row.find('input').val('');
            row.find(addButtonClass).replaceWith(removeButtonHtml);
            wrapper.append(row);
        });

        // Remove row
        wrapper.on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(this).closest('.attribute-row').remove();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
        < /scrip> <
        script >
            document.addEventListener('DOMContentLoaded', function() {
                // সব ডিলিট বাটনে ইভেন্ট লিসেনার লাগাবে
                document.querySelectorAll('.confirmDlete').forEach(function(element) {
                    element.addEventListener('click', function(e) {
                        e.preventDefault();

                        const module = this.dataset.module; // যেমন: attribute
                        const id = this.dataset.id; // id

                        Swal.fire({
                            title: 'Are you sure?',
                            text: `You want to delete this ${module}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // যদি AJAX দিয়ে delete করো:
                                // অথবা নিচের লাইন দিয়ে redirect করো
                                window.location.href =
                                    `/admin/delete-${module}/${id}`;
                            }
                        });
                    });
                });
            });
    </script>
@endsection
