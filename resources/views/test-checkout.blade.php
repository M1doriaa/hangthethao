<!DOCTYPE html>
<html>
<head>
    <title>Test Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .item { border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Test Checkout Page</h1>
    
    <h2>Cart Items ({{ $cartItems->count() }})</h2>
    @forelse($cartItems as $item)
        <div class="item">
            <strong>{{ $item->product->name }}</strong><br>
            Price: {{ number_format($item->price) }}₫<br>
            Quantity: {{ $item->quantity }}<br>
            @if($item->variant)
                Variant: {{ $item->variant->size }} - {{ $item->variant->color }}
            @endif
        </div>
    @empty
        <p>No cart items found</p>
    @endforelse
    
    <h2>Total: {{ number_format($total) }}₫</h2>
    
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="phone" placeholder="Phone" required><br><br>
        <input type="text" name="city" placeholder="City" required><br><br>
        <input type="text" name="district" placeholder="District" required><br><br>
        <input type="text" name="ward" placeholder="Ward" required><br><br>
        <input type="text" name="address" placeholder="Address" required><br><br>
        <select name="payment_method" required>
            <option value="cod">COD</option>
            <option value="bank_transfer">Bank Transfer</option>
        </select><br><br>
        <button type="submit">Place Order</button>
    </form>
</body>
</html>
