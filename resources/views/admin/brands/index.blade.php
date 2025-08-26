@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Brands Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Brands</li>
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
                                <h3 class="card-title">Brands</h3>
                                @if ($brandsModule['full_access'] == 1)
                                    <a style="max-width: 150px; float:right; display: inline-block;"
                                        href="{{ url('admin/brand/create') }}" class="btn btn-block btn-primary">Add
                                        Brands
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
                                <table id="brands" class="table-bordered table-striped table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>URL</th>
                                            <th>Created On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($brands as $brand)
                                            <tr>
                                                <td>{{ $brand->id }}</td>
                                                <td>{{ $brand->name }}</td>
                                                {{-- <td>{{ $category->parentcategory->name ?? '' }}</td> --}}
                                                <td>{{ $brand->url }}</td>
                                                <td>{{ $brand->created_at->format(' M j, Y g:i A') }}</td>
                                                <td>
                                                    @if ($brandsModule['edit_access'] == 1 || $brandsModule['full_access'] == 1)
                                                        @if ($brand->status == 1)
                                                            <a class="updatebrandStatus" data-brand-id="{{ $brand->id }}"
                                                                style="color:#3f6ed3" href="javascript:void(0)">
                                                                <i class="fas fa-toggle-on" data-status="Active"></i>
                                                            </a>
                                                        @else
                                                            <a class="updatebrandStatus" data-brand-id="{{ $brand->id }}"
                                                                style="color:grey" href="javascript:void(0)">
                                                                <i class="fas fa-toggle-off" data-status="Inactive"></i>
                                                            </a>
                                                        @endif&nbsp;&nbsp;<a
                                                            href="{{ url('admin/brand/' . $brand->id . '/edit') }}">
                                                            <i class="fas fa-edit"></i></a>&nbsp;&nbsp;
                                                        @if ($brandsModule['full_access'] == 1)
                                                            <form action="{{ route('brand.destroy', $brand->id) }}"
                                                                method="post" style="display:inline-block;"
                                                                onsubmit="return confirm('Are you sure to delete this brand?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    style="border: none; background: none; color: #3f6ed3;"
                                                                    tabindex="Delete Brand">
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
