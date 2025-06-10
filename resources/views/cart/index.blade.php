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
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .cart-item-info h6 {
        color: #333;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .cart-item-details {
        color: #666;
        font-size: 0.9rem;
    }
    
    .cart-item-price {
        color: var(--primary-red);
        font-weight: bold;
        font-size: 1.1rem;
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
        width: 60px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px;
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
        justify-content: between;
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
    
    .remove-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .remove-btn:hover {
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
    
    @media (max-width: 768px) {
        .cart-item {
            padding: 15px;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
        }
        
        .quantity-controls {
            flex-direction: column;
            gap: 5px;
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
                    <div id="cart-items-container">
                        @foreach($cartItems as $key => $item)
                            <div class="cart-item" data-key="{{ $key }}">
                                <div class="row align-items-center">
                                    <div class="col-md-2 col-3">
                                        <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100x100/cccccc/ffffff?text=Product' }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="cart-item-image">
                                    </div>
                                    <div class="col-md-4 col-9">
                                        <div class="cart-item-info">
                                            <h6>{{ $item['name'] }}</h6>
                                            <div class="cart-item-details">
                                                @if($item['size'])
                                                    <span>Kích thước: <strong>{{ $item['size'] }}</strong></span><br>
                                                @endif
                                                @if($item['color'])
                                                    <span>Màu sắc: <strong>{{ $item['color'] }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 text-center">
                                        <div class="cart-item-price">
                                            {{ number_format($item['price']) }}₫
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="quantity-controls">
                                            <button class="quantity-btn" onclick="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                   class="quantity-input" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   onchange="updateQuantity('{{ $key }}', this.value)">
                                            <button class="quantity-btn" onclick="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-12 text-end">
                                        <button class="remove-btn" onclick="removeFromCart('{{ $key }}')">
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
                        </a>                                        <button class="btn btn-outline-danger" id="clear-cart-btn">
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
@if(!empty($cartItems))
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="section-title text-center mb-4">Sản phẩm gợi ý</h3>
        <div class="row g-4">
            <!-- Add recommended products here -->
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <img src="https://via.placeholder.com/250x200/cccccc/ffffff?text=Recommended+1" class="card-img-top" alt="Sản phẩm gợi ý">
                    <div class="card-body">
                        <h6 class="card-title">Áo thun thể thao nam</h6>
                        <div class="rating mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="price mb-2">299.000₫</p>
                        <button class="btn btn-outline-primary btn-sm">Thêm vào giỏ</button>
                    </div>
                </div>
            </div>
            <!-- Repeat for more products... -->
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
    // Cart functionality is now handled by cart.js
    // No additional JavaScript needed here
</script>
@endpush
