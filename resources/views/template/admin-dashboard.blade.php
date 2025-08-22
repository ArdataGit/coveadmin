<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Admin Dashboard | Edurock - Education LMS Template')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aos.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"><!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- Additional CSS for Admin Panel -->
    <style>
        /* Header Styling */
        .admin-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .is_dark .admin-header {
            background-color: #1a1a1a;
            border-bottom: 1px solid #333;
        }
        .admin-header .logo img {
            height: 40px;
        }
        .admin-header .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-header .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .admin-header .notification {
            position: relative;
        }
        .admin-header .notification .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            padding: 3px 6px;
        }
        /* Sidebar Enhancements */
        .dashboard__inner {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .is_dark .dashboard__inner {
            background-color: #2c2c2c;
        }
        .dashboard__nav__title img {
            width: 100px;
            margin-bottom: 10px;
        }
        .dashboard__nav ul li a {
            list-style: none !important ;
            padding: 12px 15px;
            border-radius: 6px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dashboard__nav ul li a:hover {
            background-color: #e9ecef;
        }
        .is_dark .dashboard__nav ul li a:hover {
            background-color: #3a3a3a;
        }
        .dashboard__nav ul li.active > a {
            background-color: #007bff;
            list-style: none !important ;
            color: #fff;
        }
        .dashboard__nav ul li .dropdown {
            list-style: none !important ;
            padding-left: 30px;
        }
        .dashboard__nav ul li .dropdown li a {
            list-style: none !important ;
            padding: 8px 15px;
            font-size: 0.95rem;
        }
        /* Content Area */
        .dashboard-content {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 80px);
        }
        .is_dark .dashboard-content {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        /* Mobile Sidebar Toggle */
        .sidebar-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        @media (max-width: 991px) {
            .sidebar-toggle {
                display: block;
            }
            .dashboard__inner {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100%;
                z-index: 1001;
                transition: left 0.3s;
            }
            .dashboard__inner.active {
                left: 0;
            }
            .dashboard-content {
                margin-left: 0 !important;
            }
        }
    </style>

    <script>
        // Theme handling (avoid FOUC)
        if (localStorage.getItem("theme-color") === "dark" || (!("theme-color" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
            document.documentElement.classList.add("is_dark");
        }
        if (localStorage.getItem("theme-color") === "light") {
            document.documentElement.classList.remove("is_dark");
        }
    </script>
</head>

<body class="body__wrapper">
    <!-- Preloader -->
    <div id="back__preloader">
        <div id="back__circle_loader"></div>
        <div class="back__loader_logo">
            <img loading="lazy" src="{{ asset('img/pre.png') }}" alt="Preload">
        </div>
    </div>

    <!-- Dark/Light Switcher -->
    <div class="mode_switcher my_switcher">
        <button id="light--to-dark-button" class="light align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon dark__mode" viewBox="0 0 512 512">
                <path d="M160 136c0-30.62 4.51-61.61 16-88C99.57 81.27 48 159.32 48 248c0 119.29 96.71 216 216 216 88.68 0 166.73-51.57 200-128-26.39 11.49-57.38 16-88 16-119.29 0-216-96.71-216-216z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon light__mode" viewBox="0 0 512 512">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M256 48v48M256 416v48M403.08 108.92l-33.94 33.94M142.86 369.14l-33.94 33.94M464 256h-48M96 256H48M403.08 403.08l-33.94-33.94M142.86 142.86l-33.94-33.94"/>
                <circle cx="256" cy="256" r="80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"/>
            </svg>
            <span class="light__mode">Light</span>
            <span class="dark__mode">Dark</span>
        </button>
    </div>

    <main class="main_wrapper overflow-hidden">
        <!-- Header -->
        <header class="admin-header">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img loading="lazy" src="{{ asset('img/logo/logo_1.png') }}" 
                 class="logo-light" style="height: auto;width: 17rem;" alt="Edurock Logo">
            <img loading="lazy" src="{{ asset('img/logo/logo_2.png') }}" 
                 class="logo-dark" style="height: auto;width: 17rem;" alt="Edurock Logo Dark">
                </a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="notification">
                    <i class="icofont-bell"></i>
                    <span class="badge bg-danger text-white">3</span>
                </div>
                <div class="user-profile">
                    <img loading="lazy" src="{{ asset('img/user-placeholder.png') }}" alt="User">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->name ?? 'Micle Obema' }}</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('dashboard/admin-profile') }}">Profile</a></li>
                            <li><a class="dropdown-item" href="{{ url('dashboard/admin-settings') }}">Settings</a></li>
                            <li><a class="dropdown-item" href="{{ url('logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-toggle">
                    <i class="icofont-navigation-menu"></i>
                </div>
            </div>
        </header>

        <!-- Dashboard Area -->
        <div class="dashboardarea sp_bottom_100">
            <div class="dashboard">
                <div class="container-fluid full__width__padding">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-12">
                            <div class="dashboard__inner sticky-top">
                                <!-- <div style="place-self: center;">
                                <img loading="lazy" src="{{ asset('img/logo/logo_1.png') }}" alt="Edurock Logo">
                                </div> -->
                                <div class="dashboard__nav__title">
                                    <h6>Welcome, {{ Auth::user()->name ?? 'Micle Obema' }}</h6>
                                </div>
                                <div class="dashboard__nav">
                                    <ul>
                                        <li style="list-style: none;">
                                            <a class="{{ Route::is('admin.dashboard') ? 'active' : '' }} no-dot" href="{{ url('dashboard/admin-dashboard') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                </svg>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li class="menu-item-has-children" style="list-style: none;">
                                            <a href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                                </svg>
                                                <span>Master Data</span>
                                            </a>
                                            <ul class="dropdown">
                                                <li><a href="{{ url('dashboard/master-fasilitas') }}">Fasilitas</a></li>
                                                <li><a href="{{ url('dashboard/master-lantai') }}">Lantai</a></li>
                                                <li><a href="{{ url('dashboard/master-lokasi') }}">Lokasi</a></li>
                                                <li><a href="{{ url('dashboard/master-tipe-kos') }}">Tipe Kamar</a></li>
                                            </ul>
                                        </li>
                                        <li style="list-style: none;">
                                            <a href="{{ url('/dashboard/master-kos') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                                    <path d="M3 12l9-9 9 9v9a2 2 0 0 1-2 2h-4v-6H9v6H5a2 2 0 0 1-2-2z"></path>
                                                </svg>
                                                <span>Kos</span>
                                            </a>
                                        </li>
                                        <li style="list-style: none;">
                                            <a href="{{ url('/dashboard/master-paket-harga') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                                    <path d="M12 1v22"></path>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg>
                                                <span>Paket Harga</span>
                                            </a>
                                        </li>
                                        <li style="list-style: none;">
                                            <a href="{{ url('/dashboard/settings') }}">
                                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                                </svg>
                                                <span>Website Settings</span>
                                            </a>
                                        </li>
                                        <li style="list-style: none;">
                                            <a href="{{ url('/dashboard/master-user') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                                Menu User
                                            </a>
                                        </li>
                                        <li style="list-style: none;">
                                            <a href="{{ url('/dashboard/transaksi') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                    class="feather feather-clock">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                Transaksi
                                            </a>
                                        </li>
                                        <li style="list-style: none;">
                                            <a href="{{ url('/dashboard/tickets') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                    class="feather feather-ticket">
                                                    <path d="M3 6h18v12H3z"></path>
                                                    <path d="M12 6v12"></path>
                                                    <path d="M9 9h6v6H9z"></path>
                                                </svg>
                                                Ticket
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="dashboard__nav__title mt-40">
                                    <h6>Action</h6>
                                </div>
                                <div class="dashboard__nav">
                                    <ul>
                                        <li style="list-style: none;">
                                            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                        class="feather feather-log-out">
                                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                        <polyline points="16 17 21 12 16 7"></polyline>
                                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                                    </svg>
                                                    Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-12">
                            <div class="dashboard-content">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JS here -->
    <script src="{{ asset('js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ asset('js/ajax-form.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <!-- jQuery (must be loaded before Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Theme handling
        if (localStorage.getItem("theme-color") === "dark" || (!("theme-color" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
            document.getElementById("light--to-dark-button")?.classList.add("dark--mode");
        }
        if (localStorage.getItem("theme-color") === "light") {
            document.getElementById("light--to-dark-button")?.classList.remove("dark--mode");
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar dropdown toggle
            document.querySelectorAll('.dashboard__nav .menu-item-has-children > a').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('active');
                    const dropdown = parent.querySelector('.dropdown');
                    dropdown.style.display = parent.classList.contains('active') ? 'block' : 'none';
                });
            });

            // Sidebar toggle for mobile
            document.querySelector('.sidebar-toggle').addEventListener('click', function() {
                document.querySelector('.dashboard__inner').classList.toggle('active');
            });

            // Close mobile sidebar when clicking outside
            document.addEventListener('click', function(e) {
                const sidebar = document.querySelector('.dashboard__inner');
                const toggle = document.querySelector('.sidebar-toggle');
                if (!sidebar.contains(e.target) && !toggle.contains(e.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
    @yield('scripts')
</body>

</html>