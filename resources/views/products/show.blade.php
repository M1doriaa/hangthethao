@extends('layouts.app')

@section('title', $product['name'] . ' - Hang The Thao')

@push('styles')
<style>
    .breadcrumb-item a {
        color: var(--primary-red);
        text-decoration: none;
    }
    
    .product-gallery {
        position: sticky;
        top: 20px;
    }
    
    .main-image {
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 15px;
    }
    
    .thumbnail-images {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .thumbnail-item {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .thumbnail-item:hover,
    .thumbnail-item.active {
        border-color: var(--primary-red);
        transform: scale(1.05);
    }
    
    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-info {
        padding: 20px 0;
    }
    
    .product-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    
    .product-brand {
        color: #666;
        margin-bottom: 15px;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .stars {
        color: #ffc107;
        font-size: 1.1rem;
    }
    
    .price-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .current-price {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-red);
    }
    
    .original-price {
        font-size: 1.2rem;
        color: #999;
        text-decoration: line-through;
        margin-left: 10px;
    }
    
    .discount-badge {
        background: var(--primary-red);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-left: 10px;
    }
    
    .size-selector,
    .color-selector {
        margin-bottom: 20px;
    }
    
    .size-options,
    .color-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    
    .size-option,
    .color-option {
        padding: 10px 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    
    .size-option:hover,
    .size-option.active,
    .color-option:hover,
    .color-option.active {
        border-color: var(--primary-red);
        background: var(--primary-red);
        color: white;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .quantity-input {
        display: flex;
        align-items: center;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .quantity-btn {
        background: #f8f9fa;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    
    .quantity-btn:hover {
        background: var(--primary-red);
        color: white;
    }
    
    .quantity-number {
        padding: 10px 20px;
        border: none;
        text-align: center;
        font-weight: bold;
        width: 80px;
    }
    
    .add-to-cart-section {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .btn-add-cart {
        flex: 1;
        padding: 15px;
        font-size: 1.1rem;
        font-weight: bold;
        border-radius: 10px;
    }
    
    .btn-buy-now {
        background: var(--primary-red);
        border-color: var(--primary-red);
        color: white;
    }
    
    .btn-buy-now:hover {
        background: var(--secondary-red);
        border-color: var(--secondary-red);
    }
    
    .product-features {
        background: white;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .feature-list {
        list-style: none;
        padding: 0;
    }
    
    .feature-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
        padding-left: 20px;
    }
    
    .feature-list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--primary-red);
        font-weight: bold;
    }
    
    .feature-list li:last-child {
        border-bottom: none;
    }
    
    .related-products {
        margin-top: 50px;
    }
    
    .related-product-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .stock-status {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .stock-available {
        color: #28a745;
        font-weight: bold;
    }
    
    .stock-out {
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">{{ $product['category_name'] }}</a></li>
                <li class="breadcrumb-item active">{{ $product['name'] }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Product Detail -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <!-- Main Image -->
                    <div class="main-image-container">
                        <img id="mainImage" src="https://via.placeholder.com/500x600/000000/ffffff?text=Áo+Khoác+Gió" 
                             alt="{{ $product['name'] }}" class="img-fluid main-image">
                    </div>
                    
                    <!-- Thumbnail Images -->
                    <div class="thumbnail-images">
                        <div class="thumbnail-item active" onclick="changeMainImage(this, 'https://via.placeholder.com/500x600/000000/ffffff?text=Áo+Khoác+Gió')">
                            <img src="https://via.placeholder.com/80x80/000000/ffffff?text=1" alt="Thumbnail 1">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, 'https://via.placeholder.com/500x600/333333/ffffff?text=Áo+Khoác+2')">
                            <img src="https://via.placeholder.com/80x80/333333/ffffff?text=2" alt="Thumbnail 2">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, 'https://via.placeholder.com/500x600/666666/ffffff?text=Áo+Khoác+3')">
                            <img src="https://via.placeholder.com/80x80/666666/ffffff?text=3" alt="Thumbnail 3">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, 'https://via.placeholder.com/500x600/999999/ffffff?text=Áo+Khoác+4')">
                            <img src="https://via.placeholder.com/80x80/999999/ffffff?text=4" alt="Thumbnail 4">
                        </div>
                        <div class="thumbnail-item" onclick="changeMainImage(this, 'https://via.placeholder.com/500x600/cccccc/000000?text=Áo+Khoác+5')">
                            <img src="https://via.placeholder.com/80x80/cccccc/000000?text=5" alt="Thumbnail 5">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Product Title -->
                    <h1 class="product-title">{{ $product['name'] }}</h1>
                    
                    <!-- Brand -->
                    <div class="product-brand">
                        <strong>Hãng:</strong> {{ $product['brand'] }}
                    </div>
                    
                    <!-- Rating -->
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $product['rating'] ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <span>({{ $product['total_reviews'] }} đánh giá)</span>
                    </div>
                    
                    <!-- Price -->
                    <div class="price-section">
                        <div class="d-flex align-items-center">
                            <span class="current-price">{{ number_format($product['price']) }}₫</span>
                            @if($product['original_price'] > $product['price'])
                                <span class="original-price">{{ number_format($product['original_price']) }}₫</span>
                                <span class="discount-badge">-{{ $product['discount_percent'] }}%</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="stock-status">
                        <i class="fas fa-check-circle {{ $product['in_stock'] ? 'text-success' : 'text-danger' }}"></i>
                        <span class="{{ $product['in_stock'] ? 'stock-available' : 'stock-out' }}">
                            {{ $product['in_stock'] ? 'Còn hàng' : 'Hết hàng' }}
                        </span>
                        @if($product['in_stock'])
                            <span class="text-muted">({{ $product['stock_quantity'] }} sản phẩm có sẵn)</span>
                        @endif
                    </div>
                    
                    <!-- Size Selector -->
                    <div class="size-selector">
                        <label class="form-label fw-bold">Kích thước:</label>
                        <div class="size-options">
                            @foreach($product['sizes'] as $index => $size)
                                <div class="size-option {{ $index === 0 ? 'active' : '' }}" onclick="selectSize(this)">
                                    {{ $size }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Color Selector -->
                    <div class="color-selector">
                        <label class="form-label fw-bold">Màu sắc:</label>
                        <div class="color-options">
                            @foreach($product['colors'] as $index => $color)
                                <div class="color-option {{ $index === 0 ? 'active' : '' }}" onclick="selectColor(this)">
                                    {{ $color }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Quantity -->
                    <div class="quantity-selector">
                        <label class="form-label fw-bold">Số lượng:</label>
                        <div class="quantity-input">
                            <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" class="quantity-number" value="1" min="1" max="{{ $product['stock_quantity'] }}">
                            <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Add to Cart -->
                    <div class="add-to-cart-section">
                        <button class="btn btn-outline-danger btn-add-cart" onclick="addToCart()">
                            <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                        </button>
                        <button class="btn btn-buy-now btn-add-cart" onclick="buyNow()">
                            <i class="fas fa-shopping-bag me-2"></i>Mua ngay
                        </button>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-3">
                                <i class="fas fa-shipping-fast text-primary fs-3 mb-2"></i>
                                <div class="small">Giao hàng nhanh</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3">
                                <i class="fas fa-undo text-success fs-3 mb-2"></i>
                                <div class="small">Đổi trả 7 ngày</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3">
                                <i class="fas fa-shield-alt text-warning fs-3 mb-2"></i>
                                <div class="small">Hàng chính hãng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Features -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="product-features">
                    <h4 class="section-title mb-3">Đặc điểm sản phẩm</h4>
                    <ul class="feature-list">
                        @foreach($product['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<section class="py-5 bg-light related-products">
    <div class="container">
        <h2 class="section-title text-center mb-5">SẢN PHẨM LIÊN QUAN</h2>
        
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="related-product-card card h-100">
                        <a href="{{ route('product.show', $relatedProduct['id']) }}">
                            <img src="https://via.placeholder.com/200x250/{{ str_pad(dechex(rand(0, 16777215)), 6, '0', STR_PAD_LEFT) }}/ffffff?text={{ urlencode($relatedProduct['name']) }}" 
                                 class="card-img-top" alt="{{ $relatedProduct['name'] }}" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body p-3">
                            <h6 class="card-title mb-2">
                                <a href="{{ route('product.show', $relatedProduct['id']) }}" class="text-decoration-none text-dark">
                                    {{ $relatedProduct['name'] }}
                                </a>
                            </h6>
                            <div class="rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $relatedProduct['rating'] ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="price mb-0">{{ number_format($relatedProduct['price']) }}₫</p>
                                    @if(isset($relatedProduct['original_price']) && $relatedProduct['original_price'] > $relatedProduct['price'])
                                        <small class="text-muted text-decoration-line-through">{{ number_format($relatedProduct['original_price']) }}₫</small>
                                    @endif
                                </div>
                                <button class="btn btn-sm btn-outline-danger" onclick="addToCartQuick({{ $relatedProduct['id'] }})">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/product-detail.js') }}"></script>
<script>
    // Legacy functions for backward compatibility
    function changeMainImage(thumbnail, imageUrl) {
        const mainImage = document.getElementById('mainImage');
        if (!mainImage) return;
        
        // Add loading state
        mainImage.style.opacity = '0.5';
        
        // Remove active class from all thumbnails
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to clicked thumbnail
        thumbnail.classList.add('active');
        
        // Load new image
        const newImage = new Image();
        newImage.onload = function() {
            mainImage.src = this.src;
            mainImage.style.opacity = '1';
        };
        newImage.src = imageUrl;
    }
    
    function selectSize(element) {
        document.querySelectorAll('.size-option').forEach(option => {
            option.classList.remove('active');
        });
        element.classList.add('active');
    }
    
    function selectColor(element) {
        document.querySelectorAll('.color-option').forEach(option => {
            option.classList.remove('active');
        });
        element.classList.add('active');
    }
    
    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const max = parseInt(quantityInput.getAttribute('max'));
        const current = parseInt(quantityInput.value);
        
        if (current < max) {
            quantityInput.value = current + 1;
        }
    }
    
    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const current = parseInt(quantityInput.value);
        
        if (current > 1) {
            quantityInput.value = current - 1;
        }    }
    
    // Functions are now handled by product-detail.js
    // Remove duplicate functions to avoid conflicts
    
    function addToCartQuick(productId) {
        // This function is for related products quick add
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id: productId,
                name: `Sản phẩm #${productId}`,
                price: 500000,
                quantity: 1,
                size: 'M',
                color: 'Đỏ',
                image: '/images/placeholder-product.jpg'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Use the notification system from product-detail.js
                if (typeof showNotification === 'function') {
                    showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                }
                if (typeof updateCartCounter === 'function') {
                    updateCartCounter(data.cart_count);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showNotification === 'function') {
                showNotification('Có lỗi xảy ra!', 'error');
            }
        });
    }    
    // Cart counter update is now handled by product-detail.js
</script>
@endpush
