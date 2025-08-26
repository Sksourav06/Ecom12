@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Products Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Products</h3>
                                @if ($productsModule['full_access'] == 1)
                                    <a style="max-width: 150px; float:right; display: inline-block;"
                                        href="{{ url('admin/product/create') }}" class="btn btn-block btn-primary">Add
                                        Product
                                    </a>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @if (Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                        <strong>Success: </strong> {{ Session::get('success_message') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        </button>
                                    </div>
                                @endif
                                <table id="products" class="table-bordered table-striped table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Product Color</th>
                                            <th>Category</th>
                                            <th>Parent Category</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $product->id }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->product_code }}</td>
                                                <td>{{ $product->product_color }}</td>
                                                <td>{{ $product['category']['name'] }}
                                                <td>
                                                    @if (isset($product['category']['parentcategory']['name']))
                                                        {{ $product['category']['parentcategory']['name'] }}
                                                    @else
                                                        ROOT
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($productsModule['edit_access'] == 1 || $productsModule['full_access'] == 1)
                                                        @if ($product->status == 1)
                                                            <a class="updateProductStatus"
                                                                data-product-id="{{ $product->id }}" style="color:#3f6ed3"
                                                                href="javascript:void(0)"><i class="fas fa-toggle-on"
                                                                data-status="Active"></i></a>@else<a
                                                                class="updateProductStatus"
                                                                data-product-id="{{ $product->id }}" style="color:grey"
                                                                href="javascript:void(0)"><i class="fas fa-toggle-off"
                                                                    data-status="Inactive"></i></a>
                                                        @endif&nbsp;&nbsp;
                                                        <a href="{{ url('admin/product/' . $product->id . '/edit') }}">
                                                            <i class="fas fa-edit"></i></a>&nbsp;&nbsp;@if ($productsModule['full_access'] == 1)
                                                            <form action="{{ route('product.destroy', $product->id) }}"
                                                                method="post" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a style="color: #3f6ed3;" class="confirmDelete"
                                                                    title="Delete product"
                                                                    data-module="product"data-id="{{ $product->id }}"
                                                                    href="{{ url('admin/delete-product/' . $product->id) }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                        @endif
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
