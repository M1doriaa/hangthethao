// Hang The Thao - Cart JavaScript
// Xử lý các chức năng giỏ hàng chung cho toàn bộ website

document.addEventListener('DOMContentLoaded', function() {
    initializeCart();
});

function initializeCart() {
    // Setup CSRF token
    setupCSRFToken();
    
    // Initialize cart counter
    updateCartCounterFromServer();
    
    // Initialize cart operations on cart page
    if (window.location.pathname === '/cart') {
        initCartPage();
    }
}

function setupCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.getAttribute('content');
    }
}

// Cart Page Specific Functions
function initCartPage() {
    // Initialize quantity controls
    initQuantityControls();
    
    // Initialize remove buttons
    initRemoveButtons();
    
    // Initialize clear cart button
    initClearCartButton();
}

function initQuantityControls() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateCartItemQuantity(this.dataset.key, this.value);
        });
    });
    
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const action = this.dataset.action;
            const currentValue = parseInt(input.value);
            
            if (action === 'increase') {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }
            
            updateCartItemQuantity(input.dataset.key, input.value);
        });
    });
}

function initRemoveButtons() {
    const removeButtons = document.querySelectorAll('.remove-item-btn');
    
    removeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const key = this.dataset.key;
            const productName = this.dataset.name;
            
            if (confirm(`Bạn có chắc muốn xóa "${productName}" khỏi giỏ hàng?`)) {
                removeCartItem(key);
            }
        });
    });
}

function initClearCartButton() {
    const clearBtn = document.getElementById('clear-cart-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
                clearCart();
            }
        });
    }
}

// Cart API Functions
async function updateCartItemQuantity(key, quantity) {
    try {
        const response = await fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                key: key,
                quantity: quantity
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Update cart summary
            updateCartSummary(result.cart_summary);
            
            // Update cart counter
            updateCartCounter(result.cart_count);
            
            // Show success notification
            showSimpleNotification('Đã cập nhật giỏ hàng!', 'success');
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error updating cart:', error);
        showSimpleNotification('Có lỗi xảy ra khi cập nhật giỏ hàng!', 'error');
    }
}

async function removeCartItem(key) {
    try {
        const response = await fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                key: key
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Remove item from DOM
            const cartItem = document.querySelector(`[data-key="${key}"]`).closest('.cart-item');
            if (cartItem) {
                cartItem.style.animation = 'slideOutLeft 0.3s ease';
                setTimeout(() => {
                    cartItem.remove();
                    
                    // Check if cart is empty
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload(); // Reload to show empty cart message
                    }
                }, 300);
            }
            
            // Update cart summary
            updateCartSummary(result.cart_summary);
            
            // Update cart counter
            updateCartCounter(result.cart_count);
            
            // Show success notification
            showSimpleNotification('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error removing item:', error);
        showSimpleNotification('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
    }
}

async function clearCart() {
    try {
        const response = await fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Reload page to show empty cart
            location.reload();
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error clearing cart:', error);
        showSimpleNotification('Có lỗi xảy ra khi xóa giỏ hàng!', 'error');
    }
}

// Helper Functions
async function updateCartCounterFromServer() {
    try {
        const response = await fetch('/cart/count');
        const result = await response.json();
        
        if (response.ok) {
            updateCartCounter(result.cart_count);
        }
    } catch (error) {
        console.error('Error fetching cart count:', error);
    }
}

function updateCartCounter(count) {
    const cartBadge = document.querySelector('.btn-danger .badge');
    if (cartBadge) {
        cartBadge.textContent = count;
        
        // Add animation
        cartBadge.style.animation = 'cartBounce 0.6s ease';
        setTimeout(() => {
            cartBadge.style.animation = '';
        }, 600);
    }
}

function updateCartSummary(summary) {
    // Update subtotal
    const subtotalEl = document.getElementById('cart-subtotal');
    if (subtotalEl && summary.subtotal !== undefined) {
        subtotalEl.textContent = formatPrice(summary.subtotal);
    }
    
    // Update shipping
    const shippingEl = document.getElementById('cart-shipping');
    if (shippingEl && summary.shipping !== undefined) {
        shippingEl.textContent = summary.shipping === 0 ? 'Miễn phí' : formatPrice(summary.shipping);
    }
    
    // Update tax
    const taxEl = document.getElementById('cart-tax');
    if (taxEl && summary.tax !== undefined) {
        taxEl.textContent = formatPrice(summary.tax);
    }
    
    // Update total
    const totalEl = document.getElementById('cart-total');
    if (totalEl && summary.total !== undefined) {
        totalEl.textContent = formatPrice(summary.total);
    }
    
    // Update item count
    const countEl = document.getElementById('cart-count');
    if (countEl && summary.total_items !== undefined) {
        countEl.textContent = `(${summary.total_items} sản phẩm)`;
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

function showSimpleNotification(message, type = 'info') {
    // Create simple notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} notification-simple`;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        min-width: 300px;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 3000);
}

// Category Page Cart Functions
window.addToCartFromCategory = function(productId, productName, productPrice, productImage) {
    // Create cart item object
    const cartItem = {
        id: productId,
        name: productName,
        price: productPrice,
        image: productImage,
        quantity: 1
    };

    // Send to server
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(cartItem)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCategoryCartNotification(productName, productImage);
            updateCartCounterFromServer();
            animateProductToCartCategory(event.target);
        } else {
            console.error('Cart Error:', data.message);
            alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
        }
    })
    .catch(error => {
        console.error('Network Error:', error);
        alert('Lỗi kết nối. Vui lòng thử lại.');
    });
};

function showCategoryCartNotification(productName, productImage) {
    // Remove existing notification
    const existingNotification = document.querySelector('.category-cart-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'category-cart-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon">
                <i class="fas fa-check-circle text-success"></i>
            </div>
            <div class="notification-text">
                <div class="notification-title">Đã thêm vào giỏ hàng!</div>
                <div class="notification-product">${productName}</div>
            </div>
            <div class="notification-image">
                <img src="${productImage}" alt="${productName}">
            </div>
        </div>
        <div class="notification-actions">
            <button class="btn btn-sm btn-outline-primary" onclick="window.location.href='/cart'">
                <i class="fas fa-shopping-cart me-1"></i>Xem giỏ hàng
            </button>
            <button class="btn btn-sm btn-secondary" onclick="this.closest('.category-cart-notification').remove()">
                Đóng
            </button>
        </div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.add('hiding');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);

    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);
}

function animateProductToCartCategory(button) {
    // Create flying element
    const productCard = button.closest('.product-card');
    const productImage = productCard.querySelector('img');
    
    if (!productImage) return;

    const flyingElement = document.createElement('div');
    flyingElement.className = 'flying-product-category';
    flyingElement.innerHTML = `<img src="${productImage.src}" alt="Flying product">`;
    
    // Position flying element
    const rect = productImage.getBoundingClientRect();
    flyingElement.style.left = rect.left + 'px';
    flyingElement.style.top = rect.top + 'px';
    flyingElement.style.width = rect.width + 'px';
    flyingElement.style.height = rect.height + 'px';
    
    document.body.appendChild(flyingElement);
    
    // Find cart icon in header
    const cartIcon = document.querySelector('.cart-icon, .fa-shopping-cart, [href*="cart"]');
    
    if (cartIcon) {
        const cartRect = cartIcon.getBoundingClientRect();
        
        // Animate to cart
        setTimeout(() => {
            flyingElement.style.transform = `translate(${cartRect.left - rect.left}px, ${cartRect.top - rect.top}px) scale(0.3)";
            flyingElement.style.opacity = '0';
        }, 100);
    } else {
        // Fallback: animate upward
        setTimeout(() => {
            flyingElement.style.transform = 'translateY(-100px) scale(0.5)';
            flyingElement.style.opacity = '0';
        }, 100);
    }
    
    // Remove flying element
    setTimeout(() => {
        flyingElement.remove();
    }, 1000);
}

// Add CSS for category notifications and animations
const categoryCSS = `
.category-cart-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border-left: 4px solid #28a745;
    padding: 0;
    z-index: 1000;
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s ease;
    max-width: 350px;
    min-width: 300px;
}

.category-cart-notification.show {
    transform: translateX(0);
    opacity: 1;
}

.category-cart-notification.hiding {
    transform: translateX(100%);
    opacity: 0;
}

.notification-content {
    display: flex;
    align-items: center;
    padding: 15px;
    gap: 12px;
}

.notification-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.notification-text {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
}

.notification-product {
    font-size: 0.9rem;
    color: #666;
}

.notification-image {
    flex-shrink: 0;
}

.notification-image img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.notification-actions {
    padding: 12px 15px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.flying-product-category {
    position: fixed;
    z-index: 1001;
    pointer-events: none;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.flying-product-category img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

@media (max-width: 768px) {
    .category-cart-notification {
        right: 10px;
        left: 10px;
        max-width: none;
        min-width: auto;
    }
    
    .notification-actions {
        flex-direction: column;
    }
    
    .notification-actions .btn {
        width: 100%;
    }
}
`;

// Add category CSS to document
const categoryStyle = document.createElement('style');
categoryStyle.textContent = categoryCSS;
document.head.appendChild(categoryStyle);

// Animation for cart item removal
const additionalCSS = `
@keyframes slideOutLeft {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(-100%);
        opacity: 0;
    }
}

@keyframes cartBounce {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
`;

// Add CSS to document
const style = document.createElement('style');
style.textContent = additionalCSS;
document.head.appendChild(style);
