<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->

            <!--end::Navbar Search-->
            <!--begin::Messages Dropdown Menu-->

            <!--end::Messages Dropdown Menu-->
            <!--begin::Notifications Dropdown Menu-->

            <!--end::Notifications Dropdown Menu-->
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img @if (!empty(Auth::guard('admin')->user()->image)) src="{{ asset('admin/images/photos/' . Auth::guard('admin')->user()->image) }}"
                        @else src="{{ asset('admin/images/photos/87778.jpg') }} @endif " class="user-image rounded-circle shadow"
                        alt="User Image" />
                    <span class="d-none d-md-inline">{{ Auth::guard('admin')->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!--begin::User Image-->
                    <li class="user-header text-bg-primary">
                        <img @if (!empty(Auth::guard('admin')->user()->image)) src="{{ asset('admin/images/photos/' . Auth::guard('admin')->user()->image) }}"
                        @else src="{{ asset('admin/images/photos/87778.jpg') }} @endif "
                        class="rounded-circle shadow" alt="User Image" />
                    <p>
                        {{ Auth::guard('admin')->user()->name }}
                        <small>Member since {{ Auth::guard('admin')->user()->created_at }}</small>
                    </p>
            </li>
            <!--end::User Image-->
            <!--begin::Menu Body-->

            <!--end::Menu Body-->
            <!--begin::Menu Footer-->
            <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
                <a href="{{ url('admin/logout') }}" class="btn btn-default btn-flat float-end">Sign out</a>
            </li>
            <!--end::Menu Footer-->
        </ul>
        </li>
        <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
