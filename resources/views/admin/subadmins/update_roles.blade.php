@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Admin Management</h3>
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
                        <!-- Fixed card opening tag -->
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
                            <form name="subadminForm" id="subadminForm" action="{{ url('admin/update-role/request') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="subadmin_id" value="{{ $id }}">

                                <div class="card-shadow-sm">
                                    <div class="card-body">
                                        @foreach ($modules as $module)
                                            @php
                                                $viewAccess = $editAccess = $fullAccess = '';
                                                foreach ($subadminRoles as $role) {
                                                    if ($role['model'] == $module) {
                                                        $viewAccess = $role['view_access'] == 1 ? 'checked' : '';
                                                        $editAccess = $role['edit_access'] == 1 ? 'checked' : '';
                                                        $fullAccess = $role['full_access'] == 1 ? 'checked' : '';
                                                        break;
                                                    }
                                                }
                                            @endphp

                                            <div class="form-group mb-3">
                                                <label><strong>{{ ucwords(str_replace('_', ' ', $module)) }}:</strong></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="{{ $module }}[view]" value="1" {{ $viewAccess }}>
                                                    <label class="form-check-label">View Access</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="{{ $module }}[edit]" value="1"
                                                        {{ $editAccess }}>
                                                    <label class="form-check-label">Edit Access</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="{{ $module }}[full]" value="1"
                                                        {{ $fullAccess }}>
                                                    <label class="form-check-label">Full Access</label>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>

                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
