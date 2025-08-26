@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Categories Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">categories</li>
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
                                <h3 class="card-title">categories</h3>
                                @if ($categoriesModule['full_access'] == 1)
                                    <a style="max-width: 150px; float:right; display: inline-block;"
                                        href="{{ url('admin/categories/create') }}" class="btn btn-block btn-primary">Add
                                        Category
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
                                <table id="categories" class="table-bordered table-striped table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Parent Category</th>
                                            <th>URL</th>
                                            <th>Created On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $category->id }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->parentcategory->name ?? '' }}</td>
                                                <td>{{ $category->url }}</td>
                                                <td>{{ $category->created_at->format(' M j, Y g:i A') }}</td>
                                                <td>
                                                    @if ($categoriesModule['edit_access'] == 1 || $categoriesModule['full_access'] == 1)
                                                        @if ($category->status == 1)
                                                            <a class="updateCategoryStatus"
                                                                data-category-id="{{ $category->id }}" style="color:#3f6ed3"
                                                                href="javascript:void(0)">
                                                                <i class="fas fa-toggle-on" data-status="Active"></i>
                                                            </a>
                                                        @else
                                                            <a class="updateCategoryStatus"
                                                                data-category-id="{{ $category->id }}" style="color:grey"
                                                                href="javascript:void(0)">
                                                                <i class="fas fa-toggle-off" data-status="Inactive"></i>
                                                            </a>
                                                        @endif&nbsp;&nbsp;
                                                        <a href="{{ url('admin/categories/' . $category->id . '/edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>&nbsp;&nbsp;
                                                        @if ($categoriesModule['full_access'] == 1)
                                                            <form action="{{ route('categories.destroy', $category->id) }}"
                                                                method="post" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a style="color: #3f6ed3;" class="confirmDelete"
                                                                    title="Delete Category" data-id="{{ $category->id }}"
                                                                    href="{{ url('admin/delete-category/' . $category->id) }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </form>
                                                        @endif
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
