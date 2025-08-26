@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manage Filter Values-{{$filter->filter_name}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Filter Values</li>
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
                            <h3 class="card-title">Filter Values</h3>
                            <a style="max-width: 150px; float:right; display: inline-block;"
                               href="{{ route('filters.index') }}" class="btn btn-block btn-primary">Filter
                            </a>
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
                            <table id="subadmins" class="table-bordered table-striped table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Values</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($filtervalues as $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ ucfirst($value->value) }}</td>
                                    <td>
                                        {{$value->sort}}
                                    </td>
                                    <td>
                                        @if ($value->status == 1)
                                        <a class="updateSubadminStatus"
                                           data-subadmin_id="{{ $filter->id }}" style="color:#3f6ed3"
                                           href="javascript:void(0)">
                                            <i class="fas fa-toggle-on" data-status="Active"></i>
                                        </a>
                                        @else
                                        <a class="updateSubadminStatus"
                                           data-subadmin_id="{{ $filter->id }}" style="color:grey"
                                           href="javascript:void(0)">
                                            <i class="fas fa-toggle-off" data-status="Inactive"></i>
                                        </a>
                                        @endif
                                    </td>

                                    <td>
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            {{-- Edit --}}
                                            <a href="{{ route('filter-values.edit', [$filter->id, $value->id]) }}" style="color:#3f6ed3">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('filter-values.destroy', [$filter->id, $value->id]) }}" method="POST"
                                                  class="confirmDelete" title="Delete Filter Value"
                                                  data-module="filter" data-id="{{ $filter->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="border: none; background: transparent; color: #3f6ed3; cursor:pointer;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
