<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | thalab - 2.0</title>

    <link rel="stylesheet" href="{{ asset('dist/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/css/main/app-dark.css') }}">
    <link rel="shortcut icon" href="{{ asset('dist/assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('dist/assets/images/logo/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('dist/assets/extensions/@fortawesome/fontawesome-free/css/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/css/shared/iconly.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    {{--    Jquery --}}
    <script src="{{ asset('dist/assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/assets/extensions/jquery/jquery.min.js') }}"></script>


    {{--<script src="{{ asset('dist/assets/datatable/dataTables.bootstrap4.min.js') }}"></script>--}}

    {{--Sweeet alert--}}
    {{-- <script src="{{ asset('dist/assets/sweetalert/sweetalert2.min.js') }}"></script> --}}
    {{--<script src="{{ asset('dist/assets/datatable/dataTables.bootstrap4.min.css') }}"></script>--}}


    <script src="{{ asset('dist/assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('dist/assets/js/app.js') }}"></script>

    <!-- Need: Apexcharts -->
    <script src="{{ asset('dist/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/assets/js/pages/dashboard.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script src="{{ asset('dist/assets/js/pages/datatables.js') }}"></script>

    @yield('script')
</head>

<body>
{{--NAVBAR--}}
<div class='layout-navbar'>
    <header class='mb-3'>
        <nav class="navbar navbar-expand navbar-light navbar-top">
            <div class="container-fluid">
                <a href="#" class="burger-btn d-block">
                    <i class="bi bi-justify fs-3"></i>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-lg-0">
                    </ul>
                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex">
                                <div class="user-name text-end me-3">
                                    @if(Auth::check())
                                        <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                                        <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                    @else
                                        <h6 class="mb-0 text-gray-600">User</h6>
                                        <p class="mb-0 text-sm text-gray-600">welcome user</p>
                                    @endif

                                </div>
                                <div class="user-img d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                        <img src="{{ asset('dist/assets/images/faces/1.jpg') }}">
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                            <li>
                                @if(Auth::check())
                                    <h6 class="dropdown-header">Hello, {{ Auth::user()->name }}</h6>
                                @else
                                    <h6 class="dropdown-header">Hello, Customer</h6>
                                @endif
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-person me-2"></i> My
                                    Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                    Settings</a></li>
                            <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a onclick="event.preventDefault();document.getElementById('logout-form').submit()"
                                   style="cursor: pointer" class="nav-link text-center" href="{{ route('logout') }}" role="button">
                                    <i class="fa fa-sign-out red"></i>Logout
                                    {{-- <i class="fa fa-sign-out" aria-hidden="true"></i> --}}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="display-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</div>
{{--NAVBAR END--}}


<div id="app">
    @include('layouts.sidebar')
    <div id="main">
        <div class="page-content">
            @yield('container')
        </div>
        @include('layouts.footer')
    </div>
</div>






</body>

</html>
