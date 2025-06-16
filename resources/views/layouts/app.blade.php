<!DOCTYPE html>
<html lang="vi">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hang The Thao - Cửa hàng thể thao uy tín')</title>
    
    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script><!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cart.css') }}" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        :root {
            --primary-red: #C41E3A;
            --secondary-red: #A01729;
            --light-gray: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--secondary-red) 100%);
            color: white;
            min-height: 400px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 80px 0;
        }
        
        .hero-image {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            z-index: 1;
        }
        
        .contact-info {
            font-size: 0.9rem;
        }
        
        .category-card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .category-card:hover {
            transform: translateY(-5px);
        }
        
        .product-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-red);
            border-color: var(--secondary-red);
        }
        
        .price {
            color: var(--primary-red);
            font-weight: bold;
        }
        
        .rating {
            color: #ffc107;
        }
        
        .section-title {
            color: var(--primary-red);
            font-weight: bold;
            margin-bottom: 2rem;
        }
        
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0 20px;
        }
        
        .social-links a {
            color: white;
            font-size: 1.2rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: var(--primary-red);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <!-- Top bar -->
        <div class="bg-light py-2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <i class="fas fa-phone text-danger me-2"></i>0849 84 48 85
                            <i class="fas fa-envelope text-danger ms-3 me-2"></i>jklamn666@gmail.com
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main navigation -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand text-danger" href="{{ route('home') }}">
                    HANG THE THAO
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                        </li>                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Sản phẩm
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('category.index', 'ao-clb') }}">
                                    <i class="fas fa-shirt me-2"></i>Áo CLB
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('category.index', 'ao-doi-tuyen') }}">
                                    <i class="fas fa-flag me-2"></i>Áo Đội Tuyển
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('category.index', 'phu-kien') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>Phụ Kiện
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('products.index') }}">
                                    <i class="fas fa-tags me-2"></i>Tất cả sản phẩm
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about">Giới thiệu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contact">Liên hệ</a>
                        </li>
                    </ul>
                      <div class="d-flex">
                        <form class="d-flex me-3" method="GET" action="{{ route('search') }}">
                            <input class="form-control me-2" type="search" name="q" placeholder="Tìm kiếm..." style="width: 200px;" value="{{ request('q') }}">
                            <button class="btn btn-outline-danger" type="submit">
                                <i class="fas fa-search"></i>
                            </button>                        </form>
                        
                        <!-- Authentication Links -->
                        @auth
                            <div class="dropdown me-2">
                                <button class="btn btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>
                                    {{ Auth::user()->name }}
                                    @if(Auth::user()->isAdmin())
                                        <span class="badge bg-danger ms-1">Admin</span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile') }}">
                                            <i class="fas fa-user-edit me-2"></i>Thông tin cá nhân
                                        </a>
                                    </li>
                                    @if(Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i>Quản trị
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger me-2">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        @endauth<a href="{{ route('cart.index') }}" class="btn btn-danger">
                            <i class="fas fa-shopping-cart"></i> 
                            <span class="badge bg-white text-danger" id="header-cart-count">
                                {{ $cartCount ?? 0 }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-danger mb-3"> HANG THE THAO</h5>
                    <p>Cửa hàng thể thao uy tín, chuyên cung cấp các sản phẩm thể thao chất lượng cao với giá cả phải chăng.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6>Sản phẩm</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Đồ bóng đá</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Phụ kiện</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6>Thông tin</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Giới thiệu</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Chính sách đổi trả</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Chính sách bảo mật</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6>Liên hệ</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i>0849 84 48 85</li>
                        <li><i class="fas fa-envelope me-2"></i>jklamn666@gmail.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Hà Nội, Việt Nam</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 Hang The Thao. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">Thiết kế bởi <span class="text-danger">Hang The Thao Team</span></p>
                </div>
            </div>
        </div>
    </footer>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Cart JavaScript - Commented out to avoid conflicts with inline cart scripts -->
    <!-- <script src="{{ asset('js/cart.js') }}"></script> -->
      @if(config('app.debug'))
        <!-- Demo script for development - Commented out to avoid conflicts -->
        <!-- <script src="{{ asset('js/demo-cart.js') }}"></script> -->
    @endif
    
    @stack('scripts')
</body>
</html>
