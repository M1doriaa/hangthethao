<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thanh toán - Hang The Thao</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #C41E3A;
            --secondary-color: #f8f9fa;
            --text-dark: #333;
            --border-color: #e9ecef;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header-checkout {
            background: linear-gradient(135deg, var(--primary-color), #A0162E);
            color: white;
            padding: 1rem 0;
        }
        
        .header-checkout .brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .checkout-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
            overflow: hidden;
        }
        
        .checkout-section {
            padding: 2rem;
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary-color);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
        }
        
        .payment-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .payment-option {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .payment-option:hover,
        .payment-option.active {
            border-color: var(--primary-color);
            background-color: #fef7f7;
        }
        
        .payment-option input[type="radio"] {
            margin-right: 0.75rem;
            transform: scale(1.2);
            accent-color: var(--primary-color);
        }
        
        .order-summary {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            border-radius: 8px;
        }
        
        .order-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .order-item:last-child {
            border-bottom: none;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .btn-checkout {
            background: linear-gradient(135deg, var(--primary-color), #A0162E);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-checkout:hover {
            background: linear-gradient(135deg, #A0162E, #8B1426);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(196, 30, 58, 0.3);
        }
          .product-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .product-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
            border: 2px solid #f8f9fa;
            flex-shrink: 0;
        }
        
        .product-info {
            flex: 1;
            min-width: 0;
        }
        
        .product-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            line-height: 1.3;
        }
        
        .product-variant {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .variant-badge {
            background: #f8f9fa;
            color: #666;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            border: 1px solid #e9ecef;
        }
        
        .product-price {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.95rem;
        }
        
        .product-quantity-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }
        
        .quantity-info {
            background: #f8f9fa;
            color: #666;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
        }
        
        .payment-details {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            display: none;
        }
        
        .payment-details.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .payment-image {
            max-width: 200px;
            width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin: 0.5rem 0;
        }
        
        .qr-code {
            max-width: 150px;
            width: 100%;
            height: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin: 0.5rem 0;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .checkout-container {
                margin: 1rem;
                border-radius: 8px;
            }
            
            .checkout-section {
                padding: 1rem;
            }
            
            .product-item {
                padding: 0.75rem;
            }
            
            .product-image {
                width: 60px;
                height: 60px;
                margin-right: 0.75rem;
            }
            
            .product-name {
                font-size: 0.9rem;
            }
            
            .product-variant {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .variant-badge {
                font-size: 0.7rem;
                padding: 0.15rem 0.4rem;
            }
            
            .product-quantity-price {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-checkout">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="brand">
                    <i class="fas fa-running me-2"></i>HANG THE THAO
                </a>
                <div class="text-white">
                    <i class="fas fa-shield-alt me-2"></i>Thanh toán an toàn
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Form thông tin -->
            <div class="col-lg-8">
                <div class="checkout-container">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <!-- Thông tin mua hàng -->
                        <div class="checkout-section">
                            <h3 class="section-title">
                                <i class="fas fa-user me-2"></i>Thông tin mua hàng
                            </h3>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                              <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" class="form-control" name="customer_email" 
                                           value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                           placeholder="Nhập email của bạn" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ và tên <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="customer_name" 
                                           value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                           placeholder="Nhập họ và tên" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại <span class="required">*</span></label>
                                    <input type="tel" class="form-control" name="customer_phone" 
                                           value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                           placeholder="Nhập số điện thoại" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tỉnh/Thành phố</label>
                                    <input type="text" class="form-control" name="province" 
                                           value="{{ old('province') }}"
                                           placeholder="Nhập tỉnh/thành phố">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quận/Huyện</label>
                                    <input type="text" class="form-control" name="district" 
                                           value="{{ old('district') }}"
                                           placeholder="Nhập quận/huyện">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phường/Xã</label>
                                    <input type="text" class="form-control" name="ward" 
                                           value="{{ old('ward') }}"
                                           placeholder="Nhập phường/xã">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ cụ thể <span class="required">*</span></label>
                                <textarea class="form-control" name="customer_address" rows="3" 
                                          placeholder="Nhập địa chỉ cụ thể (số nhà, tên đường...)" required></textarea>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="save_address">
                                <label class="form-check-label" for="save_address">
                                    Giao hàng đến địa chỉ trên
                                </label>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ghi chú (tùy chọn)</label>
                                <textarea class="form-control" name="notes" rows="2" 
                                          placeholder="Ghi chú thêm về đơn hàng"></textarea>
                            </div>
                        </div>
                        
                        <!-- Vận chuyển -->
                        <div class="checkout-section" style="border-top: 1px solid var(--border-color);">
                            <h3 class="section-title">
                                <i class="fas fa-truck me-2"></i>Vận chuyển
                            </h3>
                            
                            <div class="shipping-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-shipping-fast me-2 text-primary"></i>
                                        <strong>Vận chuyển nhanh toàn quốc</strong>
                                        <div class="text-muted small">Giao hàng trong 1-3 ngày làm việc</div>
                                    </div>
                                    <div class="text-success fw-bold">Miễn phí</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thanh toán -->
                        <div class="checkout-section" style="border-top: 1px solid var(--border-color);">
                            <h3 class="section-title">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán
                            </h3>
                              <div class="payment-section">
                                <div class="payment-option active" onclick="selectPayment(this, 'cod')">
                                    <label class="d-flex align-items-center mb-0">
                                        <input type="radio" name="payment_method" value="cod" checked>
                                        <div>
                                            <strong>Thanh toán khi giao hàng (COD)</strong>
                                            <div class="text-muted small">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="payment-option" onclick="selectPayment(this, 'bank_transfer')">
                                    <label class="d-flex align-items-center mb-0">
                                        <input type="radio" name="payment_method" value="bank_transfer">
                                        <div>
                                            <strong>Chuyển khoản ngân hàng</strong>
                                            <div class="text-muted small">Chuyển khoản trước khi giao hàng</div>
                                        </div>
                                    </label>
                                    <div class="payment-details" id="bank_transfer_details">
                                        <div class="text-center">
                                            <img src="/images/qr.jpg" alt="QR Code chuyển khoản" class="qr-code" 
                                                 onerror="this.style.display='none'">
                                            <div class="mt-2">
                                                <small class="text-muted">Quét mã QR để chuyển khoản</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="payment-option" onclick="selectPayment(this, 'momo')">
                                    <label class="d-flex align-items-center mb-0">
                                        <input type="radio" name="payment_method" value="momo">
                                        <div>
                                            <strong>Ví MoMo</strong>
                                            <div class="text-muted small">Thanh toán qua ví điện tử MoMo</div>
                                        </div>
                                    </label>
                                    <div class="payment-details" id="momo_details">
                                        <div class="text-center">
                                            <img src="/images/momo.jpg" alt="MoMo Payment" class="payment-image" 
                                                 onerror="this.style.display='none'">
                                            <div class="mt-2">
                                                <img src="/images/qr.jpg" alt="QR Code MoMo" class="qr-code" 
                                                     onerror="this.style.display='none'">
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">Quét mã QR bằng ứng dụng MoMo</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Chi tiết thanh toán -->
                            <div class="payment-details" id="paymentDetails">
                                <!-- Nội dung chi tiết sẽ được cập nhật bằng JavaScript -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Đơn hàng -->
            <div class="col-lg-4">
                <div class="checkout-container">                    <div class="checkout-section">
                        <h3 class="section-title">
                            <i class="fas fa-shopping-bag me-2"></i>Đơn hàng 
                            @if(isset($cartItems) && $cartItems->count() > 0)
                                ({{ $cartItems->count() }} sản phẩm)
                            @else
                                (0 sản phẩm)
                            @endif
                        </h3>
                          @if(isset($cartItems) && $cartItems->count() > 0)
                            @foreach($cartItems as $item)
                                <div class="product-item">
                                    <img src="{{ $item->product->main_image ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjhmOWZhIiBzdHJva2U9IiNkZWUyZTYiIHN0cm9rZS13aWR0aD0iMiIvPgogIDx0ZXh0IHg9IjQwIiB5PSIzNSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjgiIGZpbGw9IiM2Yzc1N2QiIHRleHQtYW5jaG9yPSJtaWRkbGUiPktow7RuZyBjw7MgxrFuaDwvdGV4dD4KICA8dGV4dCB4PSI0MCIgeT0iNTAiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSI2IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5TYW4gcGjhuqltPC90ZXh0Pgo8L3N2Zz4=' }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="product-image"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjhmOWZhIiBzdHJva2U9IiNkZWUyZTYiIHN0cm9rZS13aWR0aD0iMiIvPgogIDx0ZXh0IHg9IjQwIiB5PSIzNSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjgiIGZpbGw9IiM2Yzc1N2QiIHRleHQtYW5jaG9yPSJtaWRkbGUiPktow7RuZyBjw7MgxrFuaDwvdGV4dD4KICA8dGV4dCB4PSI0MCIgeT0iNTAiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSI2IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5TYW4gcGjhuqltPC90ZXh0Pgo8L3N2Zz4='; this.onerror=null;">
                                    <div class="product-info">
                                        <div class="product-name">{{ $item->product->name }}</div>
                                        @if($item->variant)
                                            <div class="product-variant">
                                                @if($item->variant->size)
                                                    <span class="variant-badge">
                                                        <i class="fas fa-ruler-combined me-1"></i>{{ $item->variant->size }}
                                                    </span>
                                                @endif
                                                @if($item->variant->color)
                                                    <span class="variant-badge">
                                                        <i class="fas fa-palette me-1"></i>{{ $item->variant->color }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="product-quantity-price">
                                            <span class="quantity-info">
                                                <i class="fas fa-times me-1"></i>{{ $item->quantity }}
                                            </span>
                                            <span class="product-price">
                                                {{ number_format(($item->variant ? $item->variant->price : $item->price) * $item->quantity, 0, ',', '.') }}₫
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Giỏ hàng trống</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                                </a>
                            </div>
                        @endif
                        
                        @if(isset($cartItems) && $cartItems->count() > 0)
                            <!-- Mã giảm giá -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Nhập mã giảm giá">
                                    <button class="btn btn-outline-primary" type="button">Áp dụng</button>
                                </div>
                            </div>
                            
                            <!-- Tổng kết -->
                            <div class="order-summary">
                                <div class="order-item">
                                    <span>Tạm tính</span>
                                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="order-item">
                                    <span>Phí vận chuyển</span>
                                    <span class="text-success">Miễn phí</span>
                                </div>
                                <div class="order-item">
                                    <span>Thuế VAT (10%)</span>
                                    <span>{{ number_format($total * 0.1, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="order-item">
                                    <span>Tổng cộng</span>
                                    <span>{{ number_format($total * 1.1, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                            
                            <button type="submit" form="checkoutForm" class="btn-checkout mt-3">
                                <i class="fas fa-lock me-2"></i>Đặt hàng
                            </button>
                        @endif
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Thông tin thanh toán được bảo mật
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    <script>
        function selectPayment(element, method) {
            // Remove active class from all payment options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('active');
            });
            
            // Hide all payment details
            document.querySelectorAll('.payment-details').forEach(detail => {
                detail.classList.remove('active');
            });
            
            // Add active class to selected option
            element.classList.add('active');
            
            // Check the radio button
            element.querySelector('input[type="radio"]').checked = true;
            
            // Show relevant payment details
            const detailsElement = document.getElementById(method + '_details');
            if (detailsElement) {
                detailsElement.classList.add('active');
            }
        }
        
        // Handle form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-checkout');
            
            // Add loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
            submitBtn.disabled = true;
            
            // Let the form submit normally - don't prevent default
            // The form will be processed by Laravel CheckoutController
        });
        
        // Initialize payment method on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set COD as default active
            const codOption = document.querySelector('.payment-option[onclick*="cod"]');
            if (codOption) {
                selectPayment(codOption, 'cod');
            }
        });
    </script>
</body>
</html>
