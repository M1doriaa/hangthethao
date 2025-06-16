@extends('layouts.app')

@section('title', $product->name . ' - Hang The Thao')

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
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
    }
    
    .related-product-card .card-img-wrapper {
        height: 250px;
        background: #f8f9fa;
    }
    
    .related-product-card .card-img-top {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .related-product-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .related-product-card .card-body {
        padding: 1rem;
    }
    
    .related-product-card .card-title {
        font-size: 0.95rem;
        line-height: 1.4;
        height: 2.8rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .text-truncate-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .related-product-card .rating {
        font-size: 0.85rem;
    }
    
    .related-product-card .price {
        font-size: 1.1rem;
    }
    
    .discount-label {
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .price-wrapper {
        min-height: 2rem;
    }
    
    @media (max-width: 768px) {
        .related-product-card .card-img-wrapper {
            height: 200px;
        }
        
        .related-product-card .card-body {
            padding: 0.75rem;
        }
        
        .related-product-card .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.875rem;
        }
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
    <div class="container">        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>                <li class="breadcrumb-item"><a href="{{ route('category.index', $product->category) }}">
                    @if($product->category === 'ao-clb')
                        Áo CLB
                    @elseif($product->category === 'ao-doi-tuyen')
                        Áo Đội Tuyển
                    @elseif($product->category === 'phu-kien')
                        Phụ Kiện
                    @else
                        {{ ucfirst(str_replace('-', ' ', $product->category)) }}
                    @endif
                </a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
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
                <div class="product-gallery">                    <!-- Main Image -->
                    <div class="main-image-container">
                        <img id="mainImage" src="{{ $product->main_image }}" 
                             alt="{{ $product->name }}" class="img-fluid main-image">
                    </div>
                    
                    <!-- Thumbnail Images -->
                    <div class="thumbnail-images">
                        @if($product->images && count($product->images) > 0)
                            @foreach($product->images as $index => $image)
                                <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage(this, '{{ $image }}')">
                                    <img src="{{ $image }}" alt="Hình {{ $index + 1 }}">
                                </div>
                            @endforeach
                        @else
                            <div class="thumbnail-item active" onclick="changeMainImage(this, '{{ $product->main_image }}')">
                                <img src="{{ $product->main_image }}" alt="Hình chính">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">                    <!-- Product Title -->
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <!-- Brand -->
                    <div class="product-brand">
                        <strong>Hãng:</strong> {{ $product->brand }}
                    </div>
                    
                    <!-- Rating -->
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <span>({{ $product->reviews_count }} đánh giá)</span>
                    </div>
                    
                    <!-- Price -->
                    <div class="price-section">
                        <div class="d-flex align-items-center">
                            <span class="current-price">{{ $product->formatted_price }}</span>
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="original-price">{{ $product->formatted_original_price }}</span>
                                @php
                                    $discountPercent = round((($product->original_price - $product->price) / $product->original_price) * 100);
                                @endphp
                                <span class="discount-badge">-{{ $discountPercent }}%</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="stock-status">
                        @php
                            $inStock = $product->stock_quantity > 0;
                        @endphp
                        <i class="fas fa-check-circle {{ $inStock ? 'text-success' : 'text-danger' }}"></i>
                        <span class="{{ $inStock ? 'stock-available' : 'stock-out' }}">
                            {{ $inStock ? 'Còn hàng' : 'Hết hàng' }}
                        </span>
                        @if($inStock)
                            <span class="text-muted">({{ $product->stock_quantity }} sản phẩm có sẵn)</span>
                        @endif
                    </div>                      <!-- Size & Color Selector -->
                    @if($product->has_variants && $product->activeVariants->count() > 0)
                        <!-- Variant Product -->
                        @php
                            $availableSizes = $product->getAvailableSizes();
                            $availableColors = $product->getAvailableColors();
                        @endphp
                        
                        @if(count($availableSizes) > 0)
                            <div class="size-selector">
                                <label class="form-label fw-bold">Kích thước:</label>
                                <div class="size-options">
                                    @foreach($availableSizes as $index => $size)
                                        <div class="size-option {{ $index === 0 ? 'active' : '' }}" data-size="{{ $size }}" onclick="selectVariantSize(this)">
                                            {{ $size }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @if(count($availableColors) > 0)
                            <div class="color-selector">
                                <label class="form-label fw-bold">Màu sắc:</label>
                                <div class="color-options">
                                    @foreach($availableColors as $index => $color)
                                        <div class="color-option {{ $index === 0 ? 'active' : '' }}" data-color="{{ $color }}" onclick="selectVariantColor(this)">
                                            {{ $color }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Simple Product -->
                        @if($product->sizes && count($product->sizes) > 0)
                            <div class="size-selector">
                                <label class="form-label fw-bold">Kích thước:</label>
                                <div class="size-options">
                                    @foreach($product->sizes as $index => $size)
                                        <div class="size-option {{ $index === 0 ? 'active' : '' }}" onclick="selectSize(this)">
                                            {{ $size }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @if($product->colors && count($product->colors) > 0)
                            <div class="color-selector">
                                <label class="form-label fw-bold">Màu sắc:</label>
                                <div class="color-options">
                                    @foreach($product->colors as $index => $color)
                                        <div class="color-option {{ $index === 0 ? 'active' : '' }}" onclick="selectColor(this)">
                                            {{ $color }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                    
                    <!-- Quantity -->
                    <div class="quantity-selector">
                        <label class="form-label fw-bold">Số lượng:</label>
                        <div class="quantity-input">
                            <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" class="quantity-number" value="1" min="1" max="{{ $product->stock_quantity }}">
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
            <div class="col-12">                <div class="product-features">
                    <h4 class="section-title mb-3">Đặc điểm sản phẩm</h4>
                    @if($product->description)
                        <div class="mb-3">
                            <p>{{ $product->description }}</p>
                        </div>
                    @endif
                    
                    @if($product->specifications && count($product->specifications) > 0)
                        <ul class="feature-list">
                            @foreach($product->specifications as $key => $value)
                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endif
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
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="related-product-card card h-100 shadow-sm">
                        <div class="card-img-wrapper position-relative overflow-hidden">
                            <a href="{{ route('products.show', $relatedProduct->id) }}">
                                <img src="{{ $relatedProduct->main_image }}" 
                                     class="card-img-top" alt="{{ $relatedProduct->name }}">
                            </a>
                            @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                @php
                                    $discountPercent = round((($relatedProduct->original_price - $relatedProduct->price) / $relatedProduct->original_price) * 100);
                                @endphp
                                <span class="discount-label position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded">
                                    -{{ $discountPercent }}%
                                </span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-2 text-truncate-2">
                                <a href="{{ route('products.show', $relatedProduct->id) }}" class="text-decoration-none text-dark">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h6>
                            <div class="rating mb-2 small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $relatedProduct->rating ? ' text-warning' : ' text-muted' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">({{ $relatedProduct->reviews_count }})</span>
                            </div>
                            <div class="mt-auto">
                                <div class="price-wrapper mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="price text-danger fw-bold">{{ $relatedProduct->formatted_price }}</span>
                                        @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                            <small class="text-muted text-decoration-line-through">{{ $relatedProduct->formatted_original_price }}</small>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-outline-danger btn-sm w-100" onclick="addToCartQuick({{ $relatedProduct->id }})">
                                    <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Product Reviews Section -->
@include('components.product-reviews', ['product' => $product])

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
      // Variant functionality
    let currentVariant = null;
    const hasVariants = {{ $product->has_variants ? 'true' : 'false' }};
    const variants = @json($product->has_variants ? $product->activeVariants : []);
    const priceRange = @json($product->getPriceRange());
    
    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        if (hasVariants && variants.length > 0) {
            // Set initial variant
            updateVariantSelection();
        }
    });
    
    function selectVariantSize(element) {
        document.querySelectorAll('.size-option').forEach(option => {
            option.classList.remove('active');
        });
        element.classList.add('active');
        updateVariantSelection();
    }
    
    function selectVariantColor(element) {
        document.querySelectorAll('.color-option').forEach(option => {
            option.classList.remove('active');
        });
        element.classList.add('active');
        updateVariantSelection();
    }
    
    function updateVariantSelection() {
        const selectedSize = document.querySelector('.size-option.active')?.dataset.size;
        const selectedColor = document.querySelector('.color-option.active')?.dataset.color;
        
        // Find matching variant
        currentVariant = variants.find(variant => 
            variant.size === selectedSize && variant.color === selectedColor
        );
        
        // Update price display
        updatePriceDisplay();
        
        // Update stock info
        updateStockInfo();
        
        // Update quantity limit
        updateQuantityLimit();
    }
    
    function updatePriceDisplay() {
        const priceElement = document.querySelector('.current-price');
        const originalPriceElement = document.querySelector('.original-price');
        const discountElement = document.querySelector('.discount-badge');
        
        if (currentVariant) {
            const currentPrice = currentVariant.sale_price || currentVariant.price;
            const originalPrice = currentVariant.sale_price ? currentVariant.price : null;
            
            // Update current price
            priceElement.textContent = new Intl.NumberFormat('vi-VN').format(currentPrice) + '₫';
            
            // Update original price and discount
            if (originalPrice && originalPrice > currentPrice) {
                originalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(originalPrice) + '₫';
                originalPriceElement.style.display = 'inline';
                
                const discount = Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
                if (discountElement) {
                    discountElement.textContent = `-${discount}%`;
                    discountElement.style.display = 'inline-block';
                }
            } else {
                originalPriceElement.style.display = 'none';
                if (discountElement) {
                    discountElement.style.display = 'none';
                }
            }
        }
    }
    
    function updateStockInfo() {
        const stockElement = document.querySelector('.stock-info');
        const stockStatusElement = stockElement?.querySelector('.stock-available, .stock-out');
        const stockQuantityElement = stockElement?.querySelector('.text-muted');
        
        if (currentVariant && stockElement) {
            const inStock = currentVariant.stock_quantity > 0;
            
            if (stockStatusElement) {
                stockStatusElement.textContent = inStock ? 'Còn hàng' : 'Hết hàng';
                stockStatusElement.className = inStock ? 'stock-available' : 'stock-out';
            }
            
            if (stockQuantityElement) {
                if (inStock) {
                    stockQuantityElement.textContent = `(${currentVariant.stock_quantity} sản phẩm có sẵn)`;
                    stockQuantityElement.style.display = 'inline';
                } else {
                    stockQuantityElement.style.display = 'none';
                }
            }
        }
    }
    
    function updateQuantityLimit() {
        const quantityInput = document.getElementById('quantity');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        
        if (currentVariant && quantityInput) {
            const maxQuantity = Math.max(1, currentVariant.stock_quantity);
            quantityInput.max = maxQuantity;
            
            // Reset quantity if it exceeds available stock
            if (parseInt(quantityInput.value) > maxQuantity) {
                quantityInput.value = Math.min(1, maxQuantity);
            }
            
            // Disable add to cart button if out of stock
            if (addToCartBtn) {
                addToCartBtn.disabled = currentVariant.stock_quantity === 0;
                addToCartBtn.textContent = currentVariant.stock_quantity === 0 ? 'Hết hàng' : 'Thêm vào giỏ hàng';
            }
        }
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
        let max;
        
        if (hasVariants && currentVariant) {
            max = currentVariant.stock_quantity;
        } else {
            max = parseInt(quantityInput.getAttribute('max'));
        }
        
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
        }
    }
    
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
        })        .then(response => response.json())
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
