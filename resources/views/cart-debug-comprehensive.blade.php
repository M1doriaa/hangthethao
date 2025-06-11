<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart Debug - Hang The Thao</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>🔍 Cart Debug Page</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Session Cart Data</h5>
                    </div>
                    <div class="card-body">
                        <pre>{{ json_encode(session('cart', []), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Cart Summary</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $cartItems = session()->get('cart', []);
                            $totalItems = array_sum(array_column($cartItems, 'quantity'));
                            $subtotal = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cartItems));
                            $shipping = $subtotal >= 500000 ? 0 : 30000;
                            $tax = $subtotal * 0.1;
                            $total = $subtotal + $shipping + $tax;
                        @endphp
                        
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total Items:</span>
                                <strong>{{ $totalItems }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <strong>{{ number_format($subtotal) }}₫</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Shipping:</span>
                                <strong>{{ number_format($shipping) }}₫</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Tax (10%):</span>
                                <strong>{{ number_format($tax) }}₫</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total:</span>
                                <strong class="text-danger">{{ number_format($total) }}₫</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="/test-add-to-cart" class="btn btn-primary">Add Test Products</a>
                        <a href="/cart" class="btn btn-success">Go to Cart Page</a>
                        <button class="btn btn-warning" onclick="clearCartTest()">Clear Cart (Test)</button>
                        <button class="btn btn-info" onclick="location.reload()">Refresh</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h5>Route Testing</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>GET Routes</h6>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="/cart" class="text-decoration-none">GET /cart</a>
                                    <span class="badge bg-secondary ms-2" id="cart-status">Testing...</span>
                                </li>
                                <li class="list-group-item">
                                    <a href="/cart/count" class="text-decoration-none">GET /cart/count</a>
                                    <span class="badge bg-secondary ms-2" id="count-status">Testing...</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>POST Routes (via JS)</h6>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="testCartUpdate()">Test /cart/update</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="testCartRemove()">Test /cart/remove</button>
                                <button class="btn btn-outline-warning btn-sm" onclick="testCartClear()">Test /cart/clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Test functions
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function clearCartTest() {
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
                alert('Clear cart result: ' + JSON.stringify(result));
                location.reload();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function testCartUpdate() {
            try {
                const response = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        key: '1_M_',
                        quantity: 3
                    })
                });
                
                const result = await response.json();
                alert('Update result: ' + JSON.stringify(result));
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function testCartRemove() {
            try {
                const response = await fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        key: '1_M_'
                    })
                });
                
                const result = await response.json();
                alert('Remove result: ' + JSON.stringify(result));
                location.reload();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function testCartClear() {
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
                alert('Clear result: ' + JSON.stringify(result));
                location.reload();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // Test route availability on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Test cart route
            fetch('/cart/count')
                .then(response => {
                    document.getElementById('count-status').textContent = response.ok ? 'OK' : 'Error';
                    document.getElementById('count-status').className = response.ok ? 'badge bg-success ms-2' : 'badge bg-danger ms-2';
                })
                .catch(error => {
                    document.getElementById('count-status').textContent = 'Error';
                    document.getElementById('count-status').className = 'badge bg-danger ms-2';
                });

            // Test cart page
            fetch('/cart', {method: 'HEAD'})
                .then(response => {
                    document.getElementById('cart-status').textContent = response.ok ? 'OK' : 'Error';
                    document.getElementById('cart-status').className = response.ok ? 'badge bg-success ms-2' : 'badge bg-danger ms-2';
                })
                .catch(error => {
                    document.getElementById('cart-status').textContent = 'Error';
                    document.getElementById('cart-status').className = 'badge bg-danger ms-2';
                });
        });
    </script>
</body>
</html>
