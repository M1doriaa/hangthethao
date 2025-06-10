// Demo script để test cart notification
// Chạy trong console để demo chức năng

// Test 1: Demo thêm sản phẩm vào giỏ hàng
function demoAddToCart() {
    const demoProduct = {
        name: 'Áo đấu Manchester United 2024',
        size: 'L',
        color: 'Đỏ',
        quantity: 2,
        price: 850000,
        image: 'https://via.placeholder.com/400x400/ff0000/ffffff?text=MU+Jersey'
    };
    
    // Show notification
    if (typeof showCartNotification === 'function') {
        showCartNotification(demoProduct, 5);
    } else {
        console.log('showCartNotification function not found');
    }
}

// Test 2: Demo cập nhật cart counter
function demoUpdateCartCounter() {
    if (typeof updateCartCounter === 'function') {
        updateCartCounter(8);
    } else {
        console.log('updateCartCounter function not found');
    }
}

// Test 3: Demo simple notification
function demoSimpleNotification() {
    if (typeof showSimpleNotification === 'function') {
        showSimpleNotification('Đây là demo notification!', 'success');
    } else {
        console.log('showSimpleNotification function not found');
    }
}

// Export functions để có thể gọi từ console
window.demoAddToCart = demoAddToCart;
window.demoUpdateCartCounter = demoUpdateCartCounter;
window.demoSimpleNotification = demoSimpleNotification;

console.log('Demo functions loaded:');
console.log('- demoAddToCart()');
console.log('- demoUpdateCartCounter()');
console.log('- demoSimpleNotification()');
