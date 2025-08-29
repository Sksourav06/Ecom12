@php
    use App\Models\Category;
    $categories = Category::getCategories('Front');
    // echo "<pre>"; print_r($categories); die;
@endphp
<!-- Topbar Start -->
<div class="container-fluid">
    <div class="row bg-secondary px-xl-5 py-2">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark" href="#">FAQs</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="#">Help</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="#">Support</a>
            </div>
        </div>
        <div class="col-lg-6 text-lg-right text-center">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark px-2" href="">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-instagram"></i>
                </a>
                <a class="text-dark pl-2" href="">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row align-items-center px-xl-5 py-3">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="" class="text-decoration-none">
                <h1 class="display-5 font-weight-semi-bold m-0"><span
                        class="text-primary font-weight-bold mr-0 border px-1">S</span>ite&nbsp;<span
                        class="text-primary font-weight-bold mr-0 border px-1">M</span>akers</h1>
            </a>
        </div>
        <div class="col-lg-6 col-6 text-left">
            <form action="">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for products">
                    <div class="input-group-append">
                        <span class="input-group-text text-primary bg-transparent">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-3 col-6 text-right">
            <a href="" class="btn border">
                <i class="fas fa-heart text-primary"></i>
                <span class="badge">0</span>
            </a>
            <a href="" class="btn border">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span class="badge">0</span>
            </a>
        </div>
    </div>
</div>
<!-- Topbar End -->

<!-- Navbar Start -->
<div class="container-fluid mb-2">
    <div class="row border-top px-xl-5">
        <!-- Left: Categories -->
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn d-flex align-items-center justify-content-between bg-primary w-100 text-white shadow-none"
                data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Categories</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="position-absolute navbar navbar-vertical navbar-light align-items-start border-top-0 border-bottom-0 bg-light collapse border p-0"
                id="navbar-vertical" style="width: calc(100% - 30px); z-index: 9;">
                <div class="navbar-nav w-100">
                    @foreach ($categories as $category)
                        @if ($category['menu_status'] == 1)
                            @if (count($category['subcategories']) > 0)
                                <div class="nav-item dropdown">
                                    <a href="{{ url($category['url']) }}" class="nav-link"
                                        data-toggle="dropdown">{{ $category['name'] }}<i
                                            class="fa fa-angle-down float-right mt-1"></i></a>
                                    <div
                                        class="dropdown-menu position-absolute bg-secondary rounded-0 w-100 m-0 border-0">
                                        @foreach ($category['subcategories'] as $subcategory)
                                            @if ($subcategory['menu_status'] == 1)
                                                <a href="{{ url($subcategory['url']) }}"
                                                    class="dropdown-item">{{ $subcategory['name'] }}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ url($category['url']) }}"
                                    class="nav-item nav-link">{{ $category['name'] }}</a>
                            @endif
                        @endif
                    @endforeach
                </div>
            </nav>
        </div>

        <!-- Right: Main Navbar -->
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-lg-0 px-0 py-3">
                <a href="" class="text-decoration-none d-block d-lg-none">
                    <h1 class="display-5 font-weight-semi-bold m-0">
                        <span class="text-primary font-weight-bold mr-1 border px-1">S</span>ite&nbsp;
                        <span class="text-primary font-weight-bold mr-1 border px-1">M</span>akers
                    </h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse justify-content-between collapse" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="/" class="nav-item nav-link">Home</a>
                        @foreach ($categories as $category)
                            @if ($category['menu_status'] == 1)
                                @if (count($category['subcategories']) > 0)
                                    <div class="nav-item dropdown">
                                        <a href="{{ url($category['url']) }}" class="nav-link dropdown-toggle"
                                            data-toggle="dropdown">{{ $category['name'] }}</a>
                                        <div class="dropdown-menu rounded-0 m-0">
                                            @foreach ($category['subcategories'] as $subcategory)
                                                @if ($subcategory['menu_status'] == 1)
                                                    @if (count($subcategory['subcategories']) > 0)
                                                        <div class="dropdown-submenu">
                                                            <a href="{{ url($subcategory['url']) }}"
                                                                class="dropdown-item">{{ $subcategory['name'] }}
                                                                <i class="fa fa-angle-right float-right mt-1"></i></a>
                                                            <div class="dropdown-menu">
                                                                @foreach ($subcategory['subcategories'] as $subsubcategory)
                                                                    @if ($subsubcategory['menu_status'] == 1)
                                                                        <a href="{{ url($subsubcategory['url']) }}"
                                                                            class="nav-item nav-link">{{ $subsubcategory['name'] }}</a>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <a href="{{ url($subcategory['url']) }}"
                                                            class="nav-item nav-link">{{ $subcategory['name'] }}</a>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ url($category['url']) }}"
                                        class="nav-item nav-link">{{ $category['name'] }}</a>
                                @endif
                            @endif
                        @endforeach

                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                    </div>
                    <div class="navbar-nav ml-auto py-0">
                        <a href="" class="nav-item nav-link">Login</a>
                        <a href="" class="nav-item nav-link">Register</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- Navbar End -->
