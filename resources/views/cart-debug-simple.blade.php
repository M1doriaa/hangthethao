<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart Debug Simple</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        }
        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
        }
        .remove-item-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 5px;
        }
        #console-output {
            background: #000;
            color: #00ff00;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1>🧪 Cart Debug Simple</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h3>Simulated Cart Items</h3>
                
                <!-- Simulated Cart Item 1 -->
                <div class="cart-item border p-3 mb-3">
                    <h6>Test Product 1</h6>
                    <div class="quantity-controls">
                        <button class="quantity-btn" data-action="decrease">-</button>
                        <input type="number" class="quantity-input" value="2" data-key="test1_M_">
                        <button class="quantity-btn" data-action="increase">+</button>
                        <button class="remove-item-btn ms-3" data-key="test1_M_" data-name="Test Product 1">🗑️</button>
                    </div>
                </div>
                
                <!-- Simulated Cart Item 2 -->
                <div class="cart-item border p-3 mb-3">
                    <h6>Test Product 2</h6>
                    <div class="quantity-controls">
                        <button class="quantity-btn" data-action="decrease">-</button>
                        <input type="number" class="quantity-input" value="1" data-key="test2_L_">
                        <button class="quantity-btn" data-action="increase">+</button>
                        <button class="remove-item-btn ms-3" data-key="test2_L_" data-name="Test Product 2">🗑️</button>
                    </div>
                </div>
                
                <button id="clear-cart-btn" class="btn btn-danger">Clear All</button>
            </div>
            
            <div class="col-md-6">
                <h3>Console Output</h3>
                <div id="console-output"></div>
                
                <div class="mt-3">
                    <button class="btn btn-primary" onclick="debugCartElements()">Debug Elements</button>
                    <button class="btn btn-success" onclick="forceInitCart()">Force Init</button>
                    <button class="btn btn-warning" onclick="testDirectAPI()">Test API</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        // Override console for debugging
        const consoleOutput = document.getElementById('console-output');
        const originalLog = console.log;
        const originalError = console.error;
        
        function addToConsole(message, type = 'log') {
            const time = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'red' : type === 'info' ? 'cyan' : '#00ff00';
            consoleOutput.innerHTML += `<div style="color: ${color}">[${time}] ${message}</div>`;
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
        }
        
        console.log = function(...args) {
            originalLog.apply(console, args);
            addToConsole(args.join(' '));
        };
        
        console.error = function(...args) {
            originalError.apply(console, args);
            addToConsole(args.join(' '), 'error');
        };
        
        // Test direct API
        async function testDirectAPI() {
            console.log('🧪 Testing direct cart/update API...');
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        key: 'test1_M_',
                        quantity: 5
                    })
                });
                
                const result = await response.json();
                console.log('API Result:', JSON.stringify(result, null, 2));
            } catch (error) {
                console.error('API Error:', error.message);
            }
        }
        
        // Wait for page load then test
        window.addEventListener('load', function() {
            console.log('🚀 Page loaded, testing cart initialization...');
            setTimeout(() => {
                debugCartElements();
            }, 1000);
        });
    </script>
</body>
</html>
