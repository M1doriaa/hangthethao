<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Cart Debug - Hang The Thao</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1>🛒 Cart Debug Test</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🧪 Test Cart Functions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Test Cart Counter -->
                        <div class="mb-3">
                            <h6>Cart Counter:</h6>
                            <button class="btn btn-danger">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge bg-light text-dark ms-1" id="cart-counter">0</span>
                            </button>
                        </div>

                        <!-- Test CSRF Token -->
                        <div class="mb-3">
                            <h6>CSRF Token:</h6>
                            <code id="csrf-display">Loading...</code>
                        </div>

                        <!-- Test addToCartHome -->
                        <div class="mb-3">
                            <h6>Test addToCartHome Function:</h6>
                            <button class="btn btn-success" onclick="testAddToCartHome()">
                                <i class="fas fa-cart-plus"></i> Test Add to Cart
                            </button>
                        </div>

                        <!-- Test Direct API Call -->
                        <div class="mb-3">
                            <h6>Test Direct API:</h6>
                            <button class="btn btn-info" onclick="testDirectAPI()">
                                <i class="fas fa-api"></i> Test Direct /cart/add
                            </button>
                        </div>

                        <!-- Console Logs -->
                        <div class="mb-3">
                            <h6>Console Logs:</h6>
                            <div id="console-logs" class="border p-2" style="height: 200px; overflow-y: auto; background: #f8f9fa;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🏠 Homepage Products</h5>
                    </div>
                    <div class="card-body">
                        <!-- Featured Product Test -->
                        <div class="mb-3">
                            <h6>Featured Product Test:</h6>
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Test Product</h6>
                                    <p class="text-muted">Price: 500,000₫</p>
                                    <button class="btn btn-sm btn-outline-danger" onclick="addToCartHome('test-1', 'Test Product', 500000, 'https://via.placeholder.com/200x200/ff0000/ffffff?text=TEST')">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Jersey Test -->
                        <div class="mb-3">
                            <h6>Jersey Test:</h6>
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">MU Jersey Test</h6>
                                    <p class="text-muted">Price: 410,000₫</p>
                                    <button class="btn btn-sm btn-outline-danger" onclick="addToCartHome('jersey-test', 'MU Home Jersey', 410000, 'https://via.placeholder.com/200x200/C41E3A/ffffff?text=MU')">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Cart JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>
    
    <script>
        // Override console.log to show in page
        const originalLog = console.log;
        const originalError = console.error;
        const logsDiv = document.getElementById('console-logs');
        
        function addLog(message, type = 'log') {
            const time = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'text-danger' : type === 'info' ? 'text-info' : 'text-dark';
            logsDiv.innerHTML += `<div class="${color}">[${time}] ${message}</div>`;
            logsDiv.scrollTop = logsDiv.scrollHeight;
        }
        
        console.log = function(...args) {
            originalLog.apply(console, args);
            addLog(args.join(' '), 'log');
        };
        
        console.error = function(...args) {
            originalError.apply(console, args);
            addLog(args.join(' '), 'error');
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 Cart Debug Page Loaded');
            
            // Show CSRF token
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            document.getElementById('csrf-display').textContent = token ? token.substring(0, 20) + '...' : 'Not found';
            console.log('CSRF Token:', token ? 'Found' : 'Missing');
            
            // Test if addToCartHome is available
            if (typeof addToCartHome === 'function') {
                console.log('✅ addToCartHome function is available');
            } else {
                console.error('❌ addToCartHome function not found');
            }
            
            // Test if cart.js functions are loaded
            if (typeof updateCartCounter === 'function') {
                console.log('✅ updateCartCounter function is available');
            } else {
                console.error('❌ updateCartCounter function not found');
            }
        });

        // Test function
        function testAddToCartHome() {
            console.log('🧪 Testing addToCartHome function...');
            if (typeof addToCartHome === 'function') {
                addToCartHome('debug-test', 'Debug Test Product', 100000, 'https://via.placeholder.com/200x200/00ff00/ffffff?text=DEBUG');
            } else {
                console.error('addToCartHome function not available');
                alert('addToCartHome function not found!');
            }
        }

        // Test direct API call
        async function testDirectAPI() {
            console.log('🔌 Testing direct API call...');
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    throw new Error('CSRF token not found');
                }

                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: 'direct-api-test',
                        name: 'Direct API Test Product',
                        price: 200000,
                        quantity: 1,
                        size: null,
                        color: null,
                        image: 'https://via.placeholder.com/200x200/0000ff/ffffff?text=API'
                    })
                });

                const result = await response.json();
                console.log('API Response:', result);
                
                if (response.ok) {
                    console.log('✅ Direct API call successful');
                    document.getElementById('cart-counter').textContent = result.cart_count || 0;
                } else {
                    console.error('❌ API call failed:', result.message);
                }
            } catch (error) {
                console.error('❌ Direct API test failed:', error.message);
            }
        }
    </script>
</body>
</html>
