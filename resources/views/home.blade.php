@extends('layouts.app')

@section('title', 'Trang chủ - Hang The Thao')

@push('styles')
<style>
    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: none;
        color: #155724;
    }
    
    .alert-success .fas.fa-check-circle {
        color: #28a745;
    }
    
    .alert-success .alert-heading {
        color: #155724;
        font-weight: 600;
    }
    
    .alert-success .btn-close {
        filter: brightness(0.8);
    }
</style>
@endpush

@section('content')
<!-- Thông báo đặt hàng thành công -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 0; border-radius: 0;">
    <div class="container">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-3" style="font-size: 1.5rem;"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-1">🎉 {{ session('success') }}</h5>
                @if(session('order_number'))
                    <p class="mb-1"><strong>Mã đơn hàng:</strong> {{ session('order_number') }}</p>
                @endif
                @if(session('order_total'))
                    <p class="mb-1"><strong>Tổng tiền:</strong> {{ session('order_total') }}</p>
                @endif
                <small class="text-muted">Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng.</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center hero-content">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">HANG THE THAO</h1>
                <p class="lead mb-4">Cửa hàng thể thao uy tín #1 Việt Nam</p>
                <div class="mb-4">
                    <div class="mb-2">
                        <i class="fas fa-phone me-2"></i>
                        <strong>Hotline:</strong> 0849 84 48 85
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>Email:</strong> jklamn666@gmail.com
                    </div>
                    <div>
                        <i class="fab fa-facebook me-2"></i>
                        <strong>Facebook:</strong> Fb.com/hangthethao48
                    </div>
                </div>
                <div class="social-links">
                    <a href="#" class="btn btn-outline-light me-2">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light me-2">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://pngfre.com/wp-content/uploads/20240109_130852.png" 
                     alt="Football Player" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">            @foreach($categories as $category)
            <div class="col-lg-4">
                <div class="category-card bg-light p-4 h-100 text-center">
                    <div class="mb-3">
                        @if($category['icon'])
                            <i class="{{ $category['icon'] }} fa-4x text-primary"></i>
                        @else
                            <img src="https://via.placeholder.com/100x100/C41E3A/ffffff?text={{ strtoupper(substr($category['name'], 0, 2)) }}" 
                                 alt="{{ $category['name'] }}" class="rounded-circle">
                        @endif
                    </div>                    <h5 class="fw-bold mb-3">{{ strtoupper($category['name']) }}</h5>
                    <p class="text-muted mb-3">{{ $category['description'] }}</p>
                    <a href="{{ route('category.index', $category['slug']) }}" class="btn btn-primary">Mua Ngay</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">        <div class="row align-items-center mb-4">
            <div class="col">
                <h2 class="section-title">PHỤ KIỆN</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('category.index', 'phu-kien') }}" class="text-decoration-none">Xem thêm <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>        <div class="row g-4">
            @foreach($accessories as $product)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="product-card card h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden">
                        <a href="{{ route('products.show', $product['id']) }}">
                            <img src="{{ $product['image'] }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $product['name'] }}"
                                 style="height: 220px; object-fit: cover;">
                        </a>                        <!-- Hover overlay with quick action -->
                        <div class="product-overlay">
                            <div class="overlay-content">
                                <button class="btn btn-light btn-sm rounded-pill" onclick="quickView({{ $product['id'] }})">
                                    <i class="fas fa-eye me-1"></i>Xem nhanh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3 text-center">
                        <h6 class="card-title mb-2 product-name">
                            <a href="{{ route('products.show', $product['id']) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($product['name'], 45) }}
                            </a>
                        </h6>
                        
                        <div class="rating mb-2">
                            @for($i = 0; $i < $product['rating']; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                            @for($i = $product['rating']; $i < 5; $i++)
                                <i class="far fa-star text-muted"></i>
                            @endfor
                        </div>
                        
                        <div class="price-section">
                            <span class="current-price fw-bold text-danger">
                                {{ isset($product['formatted_price']) ? $product['formatted_price'] : number_format($product['price']) . '₫' }}
                            </span>
                            @if(isset($product['original_price']) && $product['original_price'] > $product['price'])
                                <div>
                                    <small class="original-price text-muted text-decoration-line-through">
                                        {{ number_format($product['original_price']) }}₫
                                    </small>
                                    <span class="badge bg-danger ms-1">
                                        -{{ round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Football Jerseys Section -->
<section class="py-5">
    <div class="container">        <div class="row align-items-center mb-4">
            <div class="col">
                <h2 class="section-title">ÁO CLB</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('category.index', 'ao-clb') }}" class="text-decoration-none">Xem thêm <i class="fas fa-arrow-right"></i></a>
            </div>
        </div><div class="row g-4">            @foreach($footballJerseys as $jersey)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="product-card card h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden">
                        <a href="{{ route('products.show', $jersey['id']) }}">
                            <img src="{{ $jersey['image'] }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $jersey['name'] }}"
                                 style="height: 220px; object-fit: cover;">
                        </a>
                        <!-- Hover overlay with quick action -->
                        <div class="product-overlay">
                            <div class="overlay-content">
                                <button class="btn btn-light btn-sm rounded-pill" onclick="quickView({{ $jersey['id'] }})">
                                    <i class="fas fa-eye me-1"></i>Xem nhanh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3 text-center">
                        <h6 class="card-title mb-2 product-name">
                            <a href="{{ route('products.show', $jersey['id']) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($jersey['name'], 45) }}
                            </a>
                        </h6>
                        
                        <div class="rating mb-2">
                            @for($i = 0; $i < $jersey['rating']; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                            @for($i = $jersey['rating']; $i < 5; $i++)
                                <i class="far fa-star text-muted"></i>
                            @endfor
                        </div>
                        
                        <div class="price-section">
                            <span class="current-price fw-bold text-danger">
                                {{ isset($jersey['formatted_price']) ? $jersey['formatted_price'] : number_format($jersey['price']) . '₫' }}
                            </span>
                            @if(isset($jersey['original_price']) && $jersey['original_price'] > $jersey['price'])
                                <div>
                                    <small class="original-price text-muted text-decoration-line-through">
                                        {{ number_format($jersey['original_price']) }}₫
                                    </small>
                                    <span class="badge bg-danger ms-1">
                                        -{{ round((($jersey['original_price'] - $jersey['price']) / $jersey['original_price']) * 100) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- National Team Jerseys Section -->
<section class="py-5">
    <div class="container">        
        <div class="row align-items-center mb-4">
            <div class="col">
                <h2 class="section-title">ÁO ĐỘI TUYỂN QUỐC GIA</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('category.index', 'ao-doi-tuyen') }}" class="text-decoration-none">Xem thêm <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
          <div class="row g-4">            @foreach($nationalTeamJerseys as $jersey)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="product-card card h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden">
                        <a href="{{ route('products.show', $jersey['id']) }}">
                            <img src="{{ $jersey['image'] }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $jersey['name'] }}"
                                 style="height: 220px; object-fit: cover;">
                        </a>
                        <!-- Hover overlay with quick action -->
                        <div class="product-overlay">
                            <div class="overlay-content">
                                <button class="btn btn-light btn-sm rounded-pill" onclick="quickView({{ $jersey['id'] }})">
                                    <i class="fas fa-eye me-1"></i>Xem nhanh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3 text-center">
                        <h6 class="card-title mb-2 product-name">
                            <a href="{{ route('products.show', $jersey['id']) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($jersey['name'], 45) }}
                            </a>
                        </h6>
                        
                        <div class="rating mb-2">
                            @for($i = 0; $i < $jersey['rating']; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                            @for($i = $jersey['rating']; $i < 5; $i++)
                                <i class="far fa-star text-muted"></i>
                            @endfor
                        </div>
                        
                        <div class="price-section">
                            <span class="current-price fw-bold text-danger">
                                {{ isset($jersey['formatted_price']) ? $jersey['formatted_price'] : number_format($jersey['price']) . '₫' }}
                            </span>
                            @if(isset($jersey['original_price']) && $jersey['original_price'] > $jersey['price'])
                                <div>
                                    <small class="original-price text-muted text-decoration-line-through">
                                        {{ number_format($jersey['original_price']) }}₫
                                    </small>
                                    <span class="badge bg-danger ms-1">
                                        -{{ round((($jersey['original_price'] - $jersey['price']) / $jersey['original_price']) * 100) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h3 class="section-title">Đăng ký nhận tin</h3>
                <p class="text-muted mb-4">Nhận thông tin về sản phẩm mới và khuyến mãi đặc biệt</p>
                <div class="row g-2">
                    <div class="col-8">
                        <input type="email" class="form-control" placeholder="Nhập email của bạn...">
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary w-100">Đăng ký</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
      // Quick view function
    function quickView(productId) {
        // Redirect to product detail page
        window.location.href = `/products/${productId}`;
    }
</script>
@endpush
