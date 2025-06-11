<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Hang The Thao</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-red: #C41E3A;
            --secondary-red: #a01729;
            --sidebar-bg: #2c3e50;
            --sidebar-text: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #34495e 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--primary-red);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            min-height: 100vh;
        }

        .navbar-brand {
            color: var(--primary-red) !important;
            font-weight: bold;
        }

        .btn-primary {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
        }

        .btn-primary:hover {
            background-color: var(--secondary-red);
            border-color: var(--secondary-red);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: var(--primary-red);
            color: white;
            border-bottom: none;
        }

        .table th {
            border-top: none;
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            font-size: 0.875em;
        }

        .page-title {
            color: var(--sidebar-bg);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--secondary-red) 100%);
            color: white;
            border-radius: 10px;
        }

        .stats-card .card-body {
            padding: 20px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .stats-label {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .sidebar-header {
            padding: 20px;
            background-color: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            color: white;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .sidebar-brand:hover {
            color: var(--primary-red);
        }

        .content-wrapper {
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .main-content {
                min-height: auto;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="sidebar-header">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Admin Panel
                    </a>
                </div>
                  <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>Dashboard
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" 
                       href="{{ route('admin.products.index') }}">
                        <i class="fas fa-box"></i>Sản phẩm
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" 
                       href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i>Đơn hàng
                    </a>
                    
                    <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                    
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i>Xem website
                    </a>
                    
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>Đăng xuất
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-store me-2"></i>Hang The Thao
                        </a>
                        
                        <div class="navbar-nav ms-auto">
                            <span class="navbar-text">
                                <i class="fas fa-user-circle me-1"></i>
                                Admin User
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="content-wrapper">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden logout form -->
    <form id="logout-form" action="#" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
