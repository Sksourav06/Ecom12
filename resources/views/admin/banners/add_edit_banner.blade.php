@extends('admin.layout.layout')
@section('content')
    <main class="app-main"> <!--begin::App Content Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Banners Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <!--end::App Content Header--> <!--begin::App Content-->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-4">
                    <div class="col-md-6"> <!-- Fixed the wrong '<' symbol here -->
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">{{ $title }}</div>
                            </div> {{-- Alert messages --}} @if (Session::has('error_message'))
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <strong>Error: </strong> {{ Session::get('error_message') }} <button type="button"
                                        class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif @if (Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                        <strong>Success: </strong> {{ Session::get('success_message') }} <button
                                            type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    @endif @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                            <strong>Error:</strong> {!! $error !!} <button type="button"
                                                class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endforeach {{-- Form Start --}} @php
                                        // Check if it's edit mode $isEdit = isset($brand) && !empty($brand->id);
                                    @endphp <form
                                        action="{{ isset($banner->id) ? route('banner.update', $banner->id) : route('banner.store') }}"
                                        method="post" enctype="multipart/form-data"> @csrf @if (isset($banner->id))
                                            @method('PUT')
                                        @endif
                                        <div class="card-body">
                                            <div class="mb-3"> <label for="type">Banner Type</label> <select
                                                    class="form-control" name="type">
                                                    <option value="">Select Type</option>
                                                    <option
                                                        value="Slider"{{ old('type', $banner->type ?? '') == 'Slider' ? 'selected' : '' }}>
                                                        Slider</option>
                                                    <option
                                                        value="Fix"{{ old('type', $banner->type ?? '') == 'Fix' ? 'selected' : '' }}>
                                                        Fix</option>
                                                </select> </div>
                                            <div class="mb-3"> <label for="title">Banner Title</label> <input
                                                    type="text" class="form-control"name="title"
                                                    value="{{ old('title', $banner->title) }}"> </div>
                                            <div class="mb-3"> <label for="alt">Alt Text</label> <input
                                                    type="text" class="form-control" name="alt"
                                                    value="{{ old('alt', $banner->alt ?? '') }}"> </div>
                                            <div class="mb-3"> <label for="link">Banner Link</label> <input
                                                    type="text" class="form-control" name="link"
                                                    value="{{ old('link', $banner->link ?? '') }}"> </div>
                                            <div class="mb-3"> <label for="sort">Sort Order</label> <input
                                                    type="number" class="form-control" name="sort"
                                                    value="{{ old('sort', $banner->sort ?? '') }}"> </div>
                                            <div class="mb-3"> <label for="image">Banner Image @if (isset($banner->id))
                                                        *
                                                    @endif </label> <input type="file"
                                                    name="image" class="form-control">
                                                @if (!empty($banner->image))
                                                    <div class="mt-2"> <img
                                                            src="{{ asset('front/images/banners/' . $banner->image) }}"
                                                            width="100" alt="Banner"> </div>
                                                @endif
                                            </div>
                                            <div class="mb-3"> <label>Status</label><br> <input type="checkbox"
                                                    name="status"
                                                    value="1"{{ old('status', $banner->status ?? 1) == 1 ? 'checked' : '' }}>active
                                            </div>
                                        </div>
                                        <div class="card-footer"> <button type="submit"
                                                class="btn btn-primary">Submit</button> </div>
                                    </form> {{-- Form End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
