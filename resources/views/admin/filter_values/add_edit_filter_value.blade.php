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
                            <form action="{{ isset($filter)? route('filters.update', $filter->id) : route('filters.store') }}"
                                  method="post">

                                @csrf
                                @if (isset($filter))
                                    @method('PUT')
                                @endif

                                <div class="card-body">

                                    {{-- Category Level Dropdown --}}
                                    <div class="mb-3">
                                        <label for="category_ids">Categories*</label>
                                        <select name="category_ids[]" class="form-control" multiple>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                        @if(in_array($cat->id, old('category_ids', $selectedCategories ?? []))) selected @endif>
                                                    {{ $cat->name }}
                                                </option>

                                                @if (!empty($cat->subcategories))
                                                    @foreach ($cat->subcategories as $subcat)
                                                        <option value="{{ $subcat->id }}"
                                                                @if(in_array($subcat->id, old('category_ids', $selectedCategories ?? []))) selected @endif>
                                                            &nbsp;&nbsp;— {{ $subcat->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Hold CTRL(windows) or CMD(Mac) to select multiple</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="filter_name" class="form-label">filter_name</label>
                                        <input type="text" class="form-control" id="filter_name" name="filter_name"
                                               placeholder="Enter Category Name"
                                               value="{{ old('filter_name', $filter->filter_name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="filter_column" class="form-label">filter_column</label>
                                        <input type="text" class="form-control" id="filter_column" name="filter_column"
                                               value="{{ old('filter_column', $filter->filter_column ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="sort" class="form-label">sort</label>
                                        <input type="number" class="form-control" id="sort" name="sort"
                                               value="{{ old('sort', $category->sort ?? 0) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="status">status</label>
                                        <select name="status" class="form-select">
                                            <option value="1"{{old('status',$filter->status ?? 1)==1?'selected':''}}>Active</option>
                                            <option value="0"{{old('status',$filter->status ?? 1)==0?'selected':''}}>Inactive</option>
                                        </select>
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
