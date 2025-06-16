@extends('layouts.app')

@section('title', 'Giỏ hàng - Hang The Thao')

@push('styles')
<style>
    .cart-container {
        min-height: 60vh;
    }
    
    .cart-item {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
        transition: all 0.3s ease;
    }
    
    .cart-item:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .cart-item-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .cart-item-image:hover {
        border-color: var(--primary-red);
        transform: scale(1.02);
    }
    
    .cart-item-info h6 {
        color: #333;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.3;
    }
    
    .cart-item-details .row {
        margin: 0;
    }
    
    .cart-item-details .col-6 {
        padding-left: 0;
        padding-right: 5px;
    }
    
    .cart-item-price {
        color: var(--primary-red);
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 2px;
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .quantity-btn {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 35px;
        height: 35px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .quantity-btn:hover {
        background: var(--primary-red);
        color: white;
        border-color: var(--primary-red);
    }
    
    .quantity-input {
        width: 50px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 6px;
        font-size: 0.9rem;
    }
    
    .cart-summary {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 25px;
        position: sticky;
        top: 20px;
    }
    
    .cart-summary h5 {
        color: var(--primary-red);
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--primary-red);
        padding-bottom: 10px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding: 5px 0;
    }
    
    .summary-row.total {
        border-top: 2px solid #ddd;
        padding-top: 15px;
        margin-top: 15px;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    
    .empty-cart i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    
    .remove-btn,
    .remove-item-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .remove-btn:hover,
    .remove-item-btn:hover {
        background: #dc3545;
        color: white;
    }
    
    .continue-shopping {
        color: var(--primary-red);
        text-decoration: none;
        font-weight: 500;
    }
    
    .continue-shopping:hover {
        text-decoration: underline;
    }
    
    .updating-variant {
        opacity: 0.6;
        pointer-events: none;
        position: relative;
    }
    
    .updating-variant::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid var(--primary-red);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 10;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .cart-item-details .form-label {
        margin-bottom: 3px;
        font-weight: 600;
        color: #666;
        font-size: 0.8rem;
    }
    
    .cart-item-details .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .size-selector,
    .color-selector {
        max-width: 100%;
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .updating-variant {
        opacity: 0.6;
        pointer-events: none;
    }
    
    @media (max-width: 768px) {
        .cart-item {
            padding: 15px;
        }
        
        .cart-item-image {
            height: 100px;
        }
        
        .quantity-controls {
            justify-content: center;
            gap: 8px;
        }
        
        .size-selector,
        .color-selector {
            font-size: 0.75rem;
            padding: 0.2rem 0.4rem;
        }
        
        .cart-item-info h6 {
            font-size: 0.9rem;
        }
        
        .cart-item-price {
            font-size: 1rem;
        }
        
        .cart-summary {
            margin-top: 2rem;
            position: static;
        }
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
                <li class="breadcrumb-item active">Giỏ hàng</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Cart Content -->
<section class="py-5 cart-container">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title mb-4">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Giỏ hàng của bạn
                    <span id="cart-count" class="text-muted fs-6">
                        ({{ $cartSummary['total_items'] ?? 0 }} sản phẩm)
                    </span>
                </h2>
            </div>
        </div>
        
        @if(empty($cartItems))
            <!-- Empty Cart -->
            <div class="row">
                <div class="col-12">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h4>Giỏ hàng của bạn đang trống</h4>
                        <p class="mb-4">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi!</p>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Cart Items -->
            <div class="row">
                <div class="col-lg-8">
                    <div id="cart-items-container">                        @foreach($cartItems as $key => $item)
                            <div class="cart-item" data-cart-id="{{ $item['cart_id'] ?? $key }}" data-key="{{ $item['cart_id'] ?? $key }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2 col-4">
                                        <div class="position-relative">
                                            <img src="{{ $item['image'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y4ZjlmYSIgc3Ryb2tlPSIjZGVlMmU2IiBzdHJva2Utd2lkdGg9IjIiLz4KICA8dGV4dCB4PSI1MCIgeT0iNDUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMCIgZmlsbD0iIzZjNzU3ZCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+S2jDtG5nIGPDsyDhqo9uaDwvdGV4dD4KICA8dGV4dCB4PSI1MCIgeT0iNjAiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSI4IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5TYW4gcGjhuqltPC90ZXh0Pgo8L3N2Zz4=' }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="cart-item-image w-100"
                                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1zbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y4ZjlmYSIgc3Ryb2tlPSIjZGVlMmU2IiBzdHJva2Utd2lkdGg9IjIiLz4KICA8dGV4dCB4PSI1MCIgeT0iNDUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMCIgZmlsbD0iIzZjNzU3ZCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+S2jDtG5nIGPDsyDhqo9uaDwvdGV4dD4KICA8dGV4dCB4PSI1MCIgeT0iNjAiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSI4IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5TYW4gcGjhuqltPC90ZXh0Pgo8L3N2Zz4='; this.onerror=null;">
                                        </div>
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="col-md-5 col-8">
                                        <div class="cart-item-info">
                                            <h6 class="mb-2">{{ $item['name'] }}</h6>
                                            <div class="cart-item-details">
                                                @if(isset($item['has_variants']) && $item['has_variants'])
                                                    <div class="row g-2">
                                                        @if(isset($item['available_sizes']) && count($item['available_sizes']) > 0)
                                                            <div class="col-6">
                                                                <label class="form-label small mb-1">Size:</label>
                                                                <select class="form-select form-select-sm size-selector" 
                                                                        data-key="{{ $item['cart_id'] ?? $key }}" 
                                                                        data-current-color="{{ $item['color'] ?? '' }}">
                                                                    @foreach($item['available_sizes'] as $size)
                                                                        <option value="{{ $size }}" 
                                                                                {{ ($item['size'] ?? '') == $size ? 'selected' : '' }}>
                                                                            {{ $size }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @elseif($item['size'] ?? false)
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">Size:</small>
                                                                <span class="badge bg-light text-dark">{{ $item['size'] }}</span>
                                                            </div>
                                                        @endif
                                                        
                                                        @if(isset($item['available_colors']) && count($item['available_colors']) > 0)
                                                            <div class="col-6">
                                                                <label class="form-label small mb-1">Màu:</label>
                                                                <select class="form-select form-select-sm color-selector" 
                                                                        data-key="{{ $item['cart_id'] ?? $key }}" 
                                                                        data-current-size="{{ $item['size'] ?? '' }}">
                                                                    @foreach($item['available_colors'] as $color)
                                                                        <option value="{{ $color }}" 
                                                                                {{ ($item['color'] ?? '') == $color ? 'selected' : '' }}>
                                                                            {{ $color }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @elseif($item['color'] ?? false)
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">Màu:</small>
                                                                <span class="badge bg-light text-dark">{{ $item['color'] }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    @if($item['size'] ?? false)
                                                        <div class="mb-1">
                                                            <small class="text-muted">Size:</small>
                                                            <span class="badge bg-light text-dark ms-1">{{ $item['size'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if($item['color'] ?? false)
                                                        <div class="mb-1">
                                                            <small class="text-muted">Màu:</small>
                                                            <span class="badge bg-light text-dark ms-1">{{ $item['color'] }}</span>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="col-md-2 col-12 text-center mb-2 mb-md-0">
                                        <div class="cart-item-price">
                                            {{ number_format($item['price']) }}₫
                                        </div>
                                        <small class="text-muted">{{ number_format($item['price']) }}₫/sản phẩm</small>
                                    </div>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="col-md-2 col-8">
                                        <div class="quantity-controls justify-content-center">
                                            <button class="quantity-btn" onclick="decreaseQuantity('{{ $item['cart_id'] ?? $key }}')">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                   class="quantity-input" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   id="qty-{{ $item['cart_id'] ?? $key }}"
                                                   onchange="updateQuantityFromInput('{{ $item['cart_id'] ?? $key }}', this.value)">
                                            <button class="quantity-btn" onclick="increaseQuantity('{{ $item['cart_id'] ?? $key }}')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="text-center mt-1">
                                            <small class="text-muted">SL: {{ $item['quantity'] }}</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <div class="col-md-1 col-4 text-end">
                                        <button class="remove-item-btn" onclick="removeCartItem('{{ $item['cart_id'] ?? $key }}', '{{ $item['name'] }}')" title="Xóa sản phẩm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Cart Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('home') }}" class="continue-shopping">
                            <i class="fas fa-arrow-left me-2"></i>
                            Tiếp tục mua sắm
                        </a>
                        <button class="btn btn-outline-danger" onclick="clearEntireCart()">
                            <i class="fas fa-trash me-2"></i>
                            Xóa toàn bộ giỏ hàng
                        </button>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary" id="cart-summary">
                        <h5>Tóm tắt đơn hàng</h5>
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span id="cart-subtotal">{{ number_format($cartSummary['subtotal']) }}₫</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span id="cart-shipping">
                                @if($cartSummary['shipping'] == 0)
                                    <span class="text-success">Miễn phí</span>
                                @else
                                    {{ number_format($cartSummary['shipping']) }}₫
                                @endif
                            </span>
                        </div>
                        
                        <div class="summary-row">
                            <span>VAT (10%):</span>
                            <span id="cart-tax">{{ number_format($cartSummary['tax']) }}₫</span>
                        </div>
                        
                        <div class="summary-row total">
                            <span>Tổng cộng:</span>
                            <span id="cart-total" class="text-danger">{{ number_format($cartSummary['total']) }}₫</span>
                        </div>
                        
                        <div class="mt-4">
                            <button class="btn btn-primary w-100 btn-lg mb-2" onclick="proceedToCheckout()">
                                <i class="fas fa-credit-card me-2"></i>
                                Thanh toán
                            </button>
                            <button class="btn btn-outline-primary w-100" onclick="saveForLater()">
                                <i class="fas fa-heart me-2"></i>
                                Lưu để mua sau
                            </button>
                        </div>
                        
                        <!-- Shipping Notice -->
                        @if($cartSummary['subtotal'] < 500000)
                            <div class="alert alert-info mt-3 small">
                                <i class="fas fa-info-circle me-2"></i>
                                Mua thêm {{ number_format(500000 - $cartSummary['subtotal']) }}₫ để được miễn phí vận chuyển!
                            </div>
                        @else
                            <div class="alert alert-success mt-3 small">
                                <i class="fas fa-check-circle me-2"></i>
                                Bạn được miễn phí vận chuyển!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Recommended Products -->
@if(!empty($cartItems) && isset($recommendedProducts) && $recommendedProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="section-title text-center mb-4">Sản phẩm gợi ý</h3>
        <div class="row g-4">
            @foreach($recommendedProducts as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ $product->main_image ?? 'https://via.placeholder.com/250x200/cccccc/ffffff?text=Product' }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                    </a>
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="{{ route('products.show', $product->id) }}" 
                               class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <div class="rating mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $product->rating ? ' text-warning' : ' text-muted' }}"></i>
                            @endfor
                        </div>
                        <p class="price mb-2 text-danger fw-bold">{{ $product->formatted_price }}</p>
                        <a href="{{ route('products.show', $product->id) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
// Global cart functions using onclick handlers to avoid duplicate listeners
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Increase quantity function
function increaseQuantity(key) {
    console.log('🔼 Increase quantity for:', key);
    const input = document.getElementById('qty-' + key);
    if (input) {
        const currentValue = parseInt(input.value);
        const newValue = currentValue + 1;
        input.value = newValue;
        updateQuantityAPI(key, newValue);
    }
}

// Decrease quantity function
function decreaseQuantity(key) {
    console.log('🔽 Decrease quantity for:', key);
    const input = document.getElementById('qty-' + key);
    if (input) {
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            const newValue = currentValue - 1;
            input.value = newValue;
            updateQuantityAPI(key, newValue);
        }
    }
}

// Update quantity from direct input change
function updateQuantityFromInput(key, value) {
    console.log('📝 Update quantity from input:', key, value);
    const quantity = parseInt(value);
    if (quantity >= 1) {
        updateQuantityAPI(key, quantity);
    }
}

// Remove cart item function
function removeCartItem(key, name) {
    console.log('🗑️ Remove item:', key, name);
    removeItemAPI(key);
}

// Clear entire cart function
function clearEntireCart() {
    console.log('🧹 Clear entire cart');
    clearCartAPI();
}

// API Functions
async function updateQuantityAPI(key, quantity) {
    console.log('🔄 API Update quantity:', key, '=', quantity);
    
    // Get cart_id from the cart item
    const cartItem = document.querySelector(`[data-key="${key}"]`);
    const cartId = cartItem ? cartItem.getAttribute('data-cart-id') : key;
    
    try {
        const response = await fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ cart_id: cartId, quantity: quantity })
        });
        
        const result = await response.json();
        console.log('📡 Update response:', result);
        
        if (response.ok && result.cart_summary) {
            updateCartSummary(result.cart_summary);
        }
        
    } catch (error) {
        console.error('❌ Update error:', error);
    }
}

async function removeItemAPI(key) {
    console.log('🗑️ API Remove item:', key);
    
    // Get cart_id from the cart item
    const cartItem = document.querySelector(`[data-key="${key}"]`);
    const cartId = cartItem ? cartItem.getAttribute('data-cart-id') : key;
    
    try {
        const response = await fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ cart_id: cartId })
        });
        
        const result = await response.json();
        console.log('📡 Remove response:', result);
        
        if (response.ok) {
            // Remove item from DOM with animation
            const cartItem = document.querySelector(`[data-key="${key}"]`);
            if (cartItem) {
                cartItem.style.opacity = '0.5';
                cartItem.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    cartItem.remove();
                    
                    // Check if cart is empty
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
            
            // Update cart summary
            if (result.cart_summary) {
                updateCartSummary(result.cart_summary);
            }
        }
        
    } catch (error) {
        console.error('❌ Remove error:', error);
    }
}

async function clearCartAPI() {
    console.log('🧹 API Clear cart');
    
    try {
        const response = await fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        console.log('📡 Clear response:', result);
        
        if (response.ok) {
            location.reload();
        }
        
    } catch (error) {
        console.error('❌ Clear error:', error);
    }
}

function updateCartSummary(summary) {
    console.log('💰 Updating cart summary:', summary);
    
    if (summary.subtotal !== undefined) {
        const subtotalEl = document.getElementById('cart-subtotal');
        if (subtotalEl) {
            subtotalEl.textContent = new Intl.NumberFormat('vi-VN').format(summary.subtotal) + '₫';
        }
    }
    
    if (summary.total !== undefined) {
        const totalEl = document.getElementById('cart-total');
        if (totalEl) {
            totalEl.textContent = new Intl.NumberFormat('vi-VN').format(summary.total) + '₫';
        }
    }
    
    if (summary.tax !== undefined) {
        const taxEl = document.getElementById('cart-tax');
        if (taxEl) {
            taxEl.textContent = new Intl.NumberFormat('vi-VN').format(summary.tax) + '₫';
        }
    }
    
    if (summary.total_items !== undefined) {
        const countEl = document.getElementById('cart-count');
        if (countEl) {
            countEl.textContent = `(${summary.total_items} sản phẩm)`;
        }
    }
}

// Functions for checkout
function proceedToCheckout() {
    // Redirect to checkout page
    window.location.href = '/checkout';
}

function saveForLater() {
    console.log('Chức năng lưu giỏ hàng đang được phát triển!');
}

// Variant update functions
async function updateVariant(key, size, color) {
    console.log('🔄 Updating variant:', key, 'Size:', size, 'Color:', color);
    
    // Add loading state
    const cartItem = document.querySelector(`[data-key="${key}"]`);
    if (cartItem) {
        cartItem.classList.add('updating-variant');
    }
    
    try {
        const response = await fetch('/cart/update-variant', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                key: key, 
                size: size, 
                color: color 
            })
        });
        
        const result = await response.json();
        console.log('📡 Variant update response:', result);
        
        if (response.ok && result.success) {
            // Update price in UI
            const priceEl = cartItem.querySelector('.cart-item-price');
            if (priceEl && result.updated_item.price) {
                priceEl.textContent = new Intl.NumberFormat('vi-VN').format(result.updated_item.price) + '₫';
            }
            
            // Update cart summary
            if (result.cart_summary) {
                updateCartSummary(result.cart_summary);
            }
            
            // Update data-key if changed
            if (result.new_key && result.new_key !== result.old_key) {
                cartItem.setAttribute('data-key', result.new_key);
                
                // Update all elements with the old key
                const quantityInput = cartItem.querySelector('.quantity-input');
                if (quantityInput) {
                    quantityInput.id = 'qty-' + result.new_key;
                }
                
                const sizeSelector = cartItem.querySelector('.size-selector');
                if (sizeSelector) {
                    sizeSelector.setAttribute('data-key', result.new_key);
                }
                
                const colorSelector = cartItem.querySelector('.color-selector');
                if (colorSelector) {
                    colorSelector.setAttribute('data-key', result.new_key);
                }
            }
            
            // Show success notification
            showNotification('Đã cập nhật sản phẩm!', 'success');
        } else {
            showNotification(result.message || 'Có lỗi xảy ra!', 'error');
        }
        
    } catch (error) {
        console.error('❌ Variant update error:', error);
        showNotification('Có lỗi xảy ra khi cập nhật!', 'error');
    } finally {
        // Remove loading state
        if (cartItem) {
            cartItem.classList.remove('updating-variant');
        }
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Event listeners for size and color selectors
document.addEventListener('DOMContentLoaded', function() {
    // Size selector change event
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('size-selector')) {
            const key = e.target.getAttribute('data-key');
            const newSize = e.target.value;
            const currentColor = e.target.getAttribute('data-current-color');
            
            updateVariant(key, newSize, currentColor);
        }
    });
    
    // Color selector change event
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('color-selector')) {
            const key = e.target.getAttribute('data-key');
            const newColor = e.target.value;
            const currentSize = e.target.getAttribute('data-current-size');
            
            updateVariant(key, currentSize, newColor);
        }
    });
});

console.log('✅ Cart functions loaded with onclick handlers - No duplicate listeners');
</script>
@endpush
