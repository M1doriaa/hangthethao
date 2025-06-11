@extends('layouts.app')

@section('title', 'Thanh toán')

@push('styles')
<style>
    .checkout-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .checkout-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .section-header {
        background-color: #C41E3A;
        color: white;
        padding: 1rem 1.5rem;
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .section-content {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #C41E3A;
        box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
    }

    .payment-method {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method:hover {
        border-color: #C41E3A;
        background-color: #fef7f7;
    }

    .payment-method.active {
        border-color: #C41E3A;
        background-color: #fef7f7;
    }

    .payment-method input[type="radio"] {
        margin-right: 0.75rem;
        transform: scale(1.2);
        accent-color: #C41E3A;
    }

    .product-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .product-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 1rem;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
    }

    .product-size {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .product-price {
        font-weight: 700;
        color: #C41E3A;
        font-size: 1.1rem;
    }

    .order-summary {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #dee2e6;
    }

    .summary-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
        font-weight: 700;
        font-size: 1.1rem;
        color: #C41E3A;
    }

    .btn-checkout {
        background-color: #C41E3A;
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .btn-checkout:hover {
        background-color: #a01729;
        color: white;
    }

    .required {
        color: #C41E3A;
    }

    .shipping-info {
        background-color: #e7f3ff;
        border: 1px solid #b3d7ff;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .shipping-info h6 {
        color: #0066cc;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding: 1rem 0;
        }
        
        .section-content {
            padding: 1rem;
        }
        
        .product-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .product-image {
            margin-bottom: 1rem;
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <div class="container">
        <div class="row">
            <!-- Form thông tin khách hàng -->
            <div class="col-lg-8">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    
                    <!-- Thông tin liên hệ -->
                    <div class="checkout-card mb-4">
                        <h5 class="section-header">
                            <i class="fas fa-user me-2"></i>Thông tin liên hệ
                        </h5>
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="email">
                                        Email <span class="required">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="example@email.com" required value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="phone">
                                        Số điện thoại <span class="required">*</span>
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="0987654321" required value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Địa chỉ giao hàng -->
                    <div class="checkout-card mb-4">
                        <h5 class="section-header">
                            <i class="fas fa-map-marker-alt me-2"></i>Địa chỉ giao hàng
                        </h5>
                        <div class="section-content">
                            <div class="mb-3">
                                <label class="form-label" for="full_name">
                                    Họ và tên <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       placeholder="Nguyễn Văn A" required value="{{ old('full_name') }}">
                                @error('full_name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="city">
                                        Tỉnh/Thành phố <span class="required">*</span>
                                    </label>
                                    <select class="form-control" id="city" name="city" required>
                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                        <option value="ho-chi-minh" {{ old('city') == 'ho-chi-minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                        <option value="ha-noi" {{ old('city') == 'ha-noi' ? 'selected' : '' }}>Hà Nội</option>
                                        <option value="da-nang" {{ old('city') == 'da-nang' ? 'selected' : '' }}>Đà Nẵng</option>
                                        <option value="hai-phong" {{ old('city') == 'hai-phong' ? 'selected' : '' }}>Hải Phòng</option>
                                        <option value="can-tho" {{ old('city') == 'can-tho' ? 'selected' : '' }}>Cần Thơ</option>
                                    </select>
                                    @error('city')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="district">
                                        Quận/Huyện <span class="required">*</span>
                                    </label>
                                    <select class="form-control" id="district" name="district" required>
                                        <option value="">Chọn Quận/Huyện</option>
                                        <option value="quan-1" {{ old('district') == 'quan-1' ? 'selected' : '' }}>Quận 1</option>
                                        <option value="quan-2" {{ old('district') == 'quan-2' ? 'selected' : '' }}>Quận 2</option>
                                        <option value="quan-3" {{ old('district') == 'quan-3' ? 'selected' : '' }}>Quận 3</option>
                                    </select>
                                    @error('district')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="ward">
                                        Phường/Xã <span class="required">*</span>
                                    </label>
                                    <select class="form-control" id="ward" name="ward" required>
                                        <option value="">Chọn Phường/Xã</option>
                                        <option value="phuong-ben-nghe" {{ old('ward') == 'phuong-ben-nghe' ? 'selected' : '' }}>Phường Bến Nghé</option>
                                        <option value="phuong-nguyen-thai-binh" {{ old('ward') == 'phuong-nguyen-thai-binh' ? 'selected' : '' }}>Phường Nguyễn Thái Bình</option>
                                    </select>
                                    @error('ward')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" for="address">
                                    Địa chỉ cụ thể <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       placeholder="Số nhà, tên đường" required value="{{ old('address') }}">
                                @error('address')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="shipping-info">
                                <h6><i class="fas fa-truck me-2"></i>Thông tin vận chuyển</h6>
                                <p class="mb-1"><strong>Giao hàng tiêu chuẩn:</strong> 2-3 ngày làm việc</p>
                                <p class="mb-0"><strong>Phí vận chuyển:</strong> Miễn phí cho đơn hàng trên 500.000₫</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="checkout-card mb-4">
                        <h5 class="section-header">
                            <i class="fas fa-credit-card me-2"></i>Phương thức thanh toán
                        </h5>
                        <div class="section-content">
                            <div class="payment-method" onclick="selectPayment('cod')">
                                <input type="radio" id="cod" name="payment_method" value="cod" checked>
                                <label for="cod" class="mb-0">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    <br><small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                </label>
                            </div>
                            
                            <div class="payment-method" onclick="selectPayment('bank_transfer')">
                                <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer">
                                <label for="bank_transfer" class="mb-0">
                                    <i class="fas fa-university me-2"></i>
                                    <strong>Chuyển khoản ngân hàng</strong>
                                    <br><small class="text-muted">Chuyển khoản qua ngân hàng hoặc ví điện tử</small>
                                </label>
                            </div>
                            
                            <div class="payment-method" onclick="selectPayment('momo')">
                                <input type="radio" id="momo" name="payment_method" value="momo">
                                <label for="momo" class="mb-0">
                                    <i class="fas fa-mobile-alt me-2"></i>
                                    <strong>Ví MoMo</strong>
                                    <br><small class="text-muted">Thanh toán qua ứng dụng MoMo</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="checkout-card">
                    <h5 class="section-header">
                        <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn
                    </h5>
                    <div class="section-content">                        <!-- Danh sách sản phẩm -->
                        @foreach($cartItems as $key => $item)
                        <div class="product-item">
                            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/80x80?text=Product' }}" 
                                 alt="{{ $item['name'] }}" 
                                 class="product-image">
                            <div class="product-info">
                                <div class="product-name">{{ $item['name'] }}</div>
                                @if(isset($item['size']) && $item['size'])
                                    <div class="product-size">Kích thước: {{ $item['size'] }}</div>
                                @endif
                                @if(isset($item['color']) && $item['color'])
                                    <div class="product-color">Màu sắc: {{ $item['color'] }}</div>
                                @endif
                                <div class="product-price">
                                    {{ number_format($item['price']) }}₫ 
                                    @if($item['quantity'] > 1)
                                        x {{ $item['quantity'] }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Tóm tắt giá -->
                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($total) }}₫</span>
                            </div>
                            <div class="summary-row">
                                <span>Phí vận chuyển:</span>
                                <span>{{ $total >= 500000 ? 'Miễn phí' : '30.000₫' }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Tổng cộng:</span>
                                <span>{{ number_format($total >= 500000 ? $total : $total + 30000) }}₫</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-checkout mt-3" form="checkoutForm">
                            <i class="fas fa-lock me-2"></i>Đặt hàng
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Thông tin của bạn được bảo mật an toàn
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectPayment(method) {
    // Remove active class from all payment methods
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('active');
    });
    
    // Add active class to selected method
    document.querySelector(`input[value="${method}"]`).closest('.payment-method').classList.add('active');
    
    // Check the radio button
    document.querySelector(`input[value="${method}"]`).checked = true;
}

// Initialize first payment method as active
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.payment-method').classList.add('active');
});
</script>
@endsection
