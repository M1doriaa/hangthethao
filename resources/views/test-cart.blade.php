<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Cart Functionality - Hang The Thao</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-5">Test Cart Functionality</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-flask me-2"></i>Test Functions</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-3 w-100" onclick="testAddToCart()">
                            <i class="fas fa-cart-plus me-2"></i>Test Add to Cart Notification
                        </button>
                        
                        <button class="btn btn-info mb-3 w-100" onclick="testUpdateCounter()">
                            <i class="fas fa-sync me-2"></i>Test Update Cart Counter
                        </button>
                        
                        <button class="btn btn-success mb-3 w-100" onclick="testSimpleNotification()">
                            <i class="fas fa-bell me-2"></i>Test Simple Notification
                        </button>
                        
                        <button class="btn btn-warning mb-3 w-100" onclick="testFlyAnimation()">
                            <i class="fas fa-paper-plane me-2"></i>Test Fly Animation
                        </button>
                        
                        <hr>
                        
                        <h6>Quick Add Test Product:</h6>
                        <form id="testAddForm">
                            <div class="mb-3">
                                <label class="form-label">Product Name:</label>
                                <input type="text" class="form-control" id="testName" value="Áo đấu Bayern Munich 2024">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Size:</label>
                                    <select class="form-select" id="testSize">
                                        <option value="S">S</option>
                                        <option value="M" selected>M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Color:</label>
                                    <select class="form-select" id="testColor">
                                        <option value="Đỏ" selected>Đỏ</option>
                                        <option value="Xanh">Xanh</option>
                                        <option value="Trắng">Trắng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <label class="form-label">Quantity:</label>
                                    <input type="number" class="form-control" id="testQuantity" value="1" min="1">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Price:</label>
                                    <input type="number" class="form-control" id="testPrice" value="750000">
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger w-100 mt-3" onclick="testRealAddToCart()">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Real Cart (Backend)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Cart Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Current Cart Count:</span>
                            <span class="badge bg-danger fs-6" id="currentCartCount">
                                {{ $cartCount ?? 0 }}
                            </span>
                        </div>
                        
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-cart me-2"></i>View Cart
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>Home
                            </a>
                        </div>
                        
                        <hr>
                        
                        <h6>Console Commands:</h6>
                        <div class="bg-dark text-light p-3 rounded">
                            <code>
                                demoAddToCart()<br>
                                demoUpdateCartCounter()<br>
                                demoSimpleNotification()
                            </code>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tip:</strong> Mở Developer Console (F12) để chạy các lệnh demo!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Cart JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/product-detail.js') }}"></script>
    <script src="{{ asset('js/demo-cart.js') }}"></script>
    
    <script>
        // Test functions
        function testAddToCart() {
            const testProduct = {
                name: 'Áo đấu Real Madrid 2024',
                size: 'L',
                color: 'Trắng',
                quantity: 1,
                price: 950000,
                image: 'https://via.placeholder.com/400x400/ffffff/000000?text=Real+Madrid'
            };
            
            if (typeof showCartNotification === 'function') {
                showCartNotification(testProduct, 3);
            } else {
                alert('showCartNotification function not found');
            }
        }
        
        function testUpdateCounter() {
            if (typeof updateCartCounter === 'function') {
                const randomCount = Math.floor(Math.random() * 10) + 1;
                updateCartCounter(randomCount);
                document.getElementById('currentCartCount').textContent = randomCount;
            } else {
                alert('updateCartCounter function not found');
            }
        }
        
        function testSimpleNotification() {
            if (typeof showSimpleNotification === 'function') {
                showSimpleNotification('This is a test notification!', 'success');
            } else {
                alert('showSimpleNotification function not found');
            }
        }
        
        function testFlyAnimation() {
            if (typeof animateProductToCart === 'function') {
                animateProductToCart('https://via.placeholder.com/60x60/ff6b6b/ffffff?text=TEST');
            } else {
                alert('animateProductToCart function not found');
            }
        }
        
        async function testRealAddToCart() {
            const formData = {
                id: 'test-product-' + Date.now(),
                name: document.getElementById('testName').value,
                size: document.getElementById('testSize').value,
                color: document.getElementById('testColor').value,
                quantity: parseInt(document.getElementById('testQuantity').value),
                price: parseInt(document.getElementById('testPrice').value),
                image: 'https://via.placeholder.com/400x400/ff6b6b/ffffff?text=' + encodeURIComponent(document.getElementById('testName').value)
            };
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showCartNotification(formData, result.cart_count);
                    updateCartCounter(result.cart_count);
                    document.getElementById('currentCartCount').textContent = result.cart_count;
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            }
        }
    </script>
</body>
</html>
