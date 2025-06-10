@extends('layouts.app')

@section('title', 'Trang chủ - Hang The Thao')

@section('content')
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
                        <strong>Hotline:</strong> 0924 85 03 503
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>Email:</strong> hangthethao@gmail.com
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
                <img src="https://via.placeholder.com/400x300/ffffff/cccccc?text=Player+Image" 
                     alt="Football Player" class="img-fluid" style="max-height: 350px;">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">            <!-- Đồ bóng đá -->
            <div class="col-lg-4">
                <div class="category-card bg-light p-4 h-100 text-center">
                    <div class="mb-3">
                        <img src="https://via.placeholder.com/100x100/C41E3A/ffffff?text=⚽" 
                             alt="Đồ bóng đá" class="rounded-circle">
                    </div>
                    <h5 class="fw-bold mb-3">ĐỒ BÓNG ĐÁ</h5>
                    <p class="text-muted mb-3">Áo, quần, giày bóng đá chính hãng từ các thương hiệu nổi tiếng</p>
                    <a href="{{ route('category.index', 'ao-clb') }}" class="btn btn-primary">Mua Ngay</a>
                </div>
            </div>
            
            <!-- Phụ kiện -->
            <div class="col-lg-4">
                <div class="category-card bg-light p-4 h-100 text-center">
                    <div class="mb-3">
                        <img src="https://via.placeholder.com/100x100/C41E3A/ffffff?text=🎽" 
                             alt="Phụ kiện" class="rounded-circle">
                    </div>
                    <h5 class="fw-bold mb-3">PHỤ KIỆN</h5>
                    <p class="text-muted mb-3">Găng tay, túi xách, bình nước và các phụ kiện thể thao khác</p>
                    <a href="{{ route('category.index', 'phu-kien') }}" class="btn btn-primary">Mua Ngay</a>
                </div>
            </div>
            
            <!-- Đồ bóng rổ -->
            <div class="col-lg-4">
                <div class="category-card bg-light p-4 h-100 text-center">
                    <div class="mb-3">
                        <img src="https://via.placeholder.com/100x100/C41E3A/ffffff?text=🏀" 
                             alt="Đồ bóng rổ" class="rounded-circle">
                    </div>
                    <h5 class="fw-bold mb-3">ĐỒ BÓNG RỔ</h5>
                    <p class="text-muted mb-3">Áo, quần, giày bóng rổ từ các thương hiệu hàng đầu thế giới</p>
                    <a href="{{ route('category.index', 'ao-doi-tuyen') }}" class="btn btn-primary">Xem Ra Mắt</a>
                </div>
            </div>
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
        </div>
          <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="product-card card h-100">
                    <a href="{{ route('product.show', $product['id']) }}">
                        <img src="{{ $product['image'] }}" 
                             class="card-img-top" alt="{{ $product['name'] }}">
                    </a>
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">
                            <a href="{{ route('product.show', $product['id']) }}" class="text-decoration-none text-dark">
                                {{ $product['name'] }}
                            </a>
                        </h6>
                        <div class="rating mb-2">
                            @for($i = 0; $i < $product['rating']; $i++)
                                <i class="fas fa-star"></i>
                            @endfor                        </div>
                        <p class="price mb-0 text-center">{{ number_format($product['price']) }}₫</p>
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
        </div><div class="row g-4">
            @foreach($footballJerseys as $jersey)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="product-card card h-100">
                    <a href="{{ route('product.show', $jersey['id']) }}">
                        <img src="{{ $jersey['image'] }}" 
                             class="card-img-top" alt="{{ $jersey['name'] }}">
                    </a>
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">
                            <a href="{{ route('product.show', $jersey['id']) }}" class="text-decoration-none text-dark">
                                {{ $jersey['name'] }}
                            </a>
                        </h6>
                        <div class="rating mb-2">
                            @for($i = 0; $i < $jersey['rating']; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @for($i = $jersey['rating']; $i < 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                        <p class="price mb-0 text-center">{{ number_format($jersey['price']) }}₫</p>
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
<script>    // Smooth scrolling for anchor links
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
</script>
@endpush
