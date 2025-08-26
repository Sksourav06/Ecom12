@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Banners Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">banners</li>
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
                                <h3 class="card-title">banners</h3>
                                @if ($bannersModule['full_access'] == 1)
                                    <a style="max-width: 150px; float:right; display: inline-block;"
                                        href="{{ url('admin/banner/create') }}" class="btn btn-block btn-primary">Add
                                        Banner
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
                                            <th>Image</th>
                                            <th>Type</th>
                                            <th>Link</th>
                                            <th>Alt</th>
                                            <th>Sort</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banners as $banner)
                                            <tr>
                                                <td>
                                                    <img src="{{ asset('front/images/banners/' . $banner->image) }}"
                                                        width="100">
                                                </td>
                                                <td>{{ $banner->type }}</td>
                                                <td>{{ $banner->link }}</td>
                                                <td>{{ $banner->alt }}</td>
                                                <td>{{ $banner->sort }}</td>
                                                <td>
                                                    @if ($bannersModule['edit_access'] == 1 || $bannersModule['full_access'] == 1)
                                                        @if ($banner->status == 1)
                                                            <a class="updateBannerStatus"
                                                                data-banner-id="{{ $banner->id }}" style="color:#3f6ed3"
                                                                href="javascript:void(0)">
                                                                <i class="fas fa-toggle-on" data-status="Active"></i>
                                                            </a>
                                                        @else
                                                            <a class="updateBannerStatus"
                                                                data-banner-id="{{ $banner->id }}" style="color:grey"
                                                                href="javascript:void(0)">
                                                                <i class="fas fa-toggle-off" data-status="Inactive"></i>
                                                            </a>
                                                        @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/banner/' . $banner->id . '/edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>&nbsp;&nbsp;
                                                    @if ($bannersModule['full_access'] == 1)
                                                        <form action="{{ route('banner.destroy', $banner->id) }}"
                                                            method="post" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a style="color: #3f6ed3;" class="confirmDelete"
                                                                title="Delete Banner" data-id="{{ $banner->id }}"
                                                                href="{{ url('admin/delete-banner/' . $banner->id) }}">
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
    <script>
        $(document).on("click", ".confirmDelete", function (e) {
            e.preventDefault();

            let button = $(this); // ✅ এখানে ঠিক করলাম
            let form = button.closest('form'); // Formটি বের করা
            let redirectUrl = form.attr('action'); // ফর্ম থেকে অ্যাকশন URL নেওয়া

            // SweetAlert কনফার্মেশন
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ফর্মটি সাবমিট করা
                    form.submit();
                }
            });
        });
    </script>
@endsection
