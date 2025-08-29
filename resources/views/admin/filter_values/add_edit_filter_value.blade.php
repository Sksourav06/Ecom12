@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">{{$title}}-{{$filter->filter_name}}</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{route('filters.index')}}">Filters</a></li>
                            <li class="breadcrumb-item"><a href="{{route('filter-values.index',$filter->id)}}">Filter Value</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
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
                            <form action="{{ isset($filterValue)? route('filter-values.update', [$filter->id,$filterValue->id]) : route('filter-values.store',$filter->id) }}"
                                  method="post">

                                @csrf
                                @if (isset($filterValue))
                                    @method('PUT')
                                @endif

                                <div class="card-body">

                                    {{-- Category Level Dropdown --}}

                                    <div class="mb-3">
                                        <label for="value" class="form-label">Value</label>
                                        <input type="text" class="form-control" id="value" name="value"
                                               placeholder="Enter value"
                                               value="{{ old('value', $filtervalue->value ?? '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="sort" class="form-label">Sort</label>
                                        <input type="number" class="form-control" id="sort" name="sort"
                                               value="{{ old('sort', $filtervalue->sort ?? 0) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="1" {{ old('status', $filtervalue->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $filtervalue->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
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
@endsection
