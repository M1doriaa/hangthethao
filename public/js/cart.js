// Hang The Thao - Cart JavaScript
// Xử lý các chức năng giỏ hàng chung cho toàn bộ website

document.addEventListener('DOMContentLoaded', function() {
    initializeCart();
});

function initializeCart() {
    console.log('🔧 Initializing cart...');
    console.log('Current pathname:', window.location.pathname);
    
    // Setup CSRF token
    setupCSRFToken();
    
    // Initialize cart counter
    updateCartCounterFromServer();
    
    // Initialize cart operations on cart page
    if (window.location.pathname === '/cart' || window.location.pathname.includes('/cart')) {
        console.log('📝 Cart page detected, initializing cart controls...');
        initCartPage();
    } else {
        console.log('ℹ️ Not on cart page, skipping cart controls initialization');
    }
}

function setupCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.getAttribute('content');
        console.log('✅ CSRF token set up');
    } else {
        console.error('❌ CSRF token not found');
    }
}

// Cart Page Specific Functions
function initCartPage() {
    console.log('🛒 Initializing cart page functions...');
    
    // Wait a bit for DOM to be fully ready
    setTimeout(() => {
        console.log('🔍 DOM debugging:');
        console.log('- Quantity inputs:', document.querySelectorAll('.quantity-input').length);
        console.log('- Quantity buttons:', document.querySelectorAll('.quantity-btn').length); 
        console.log('- Remove buttons:', document.querySelectorAll('.remove-item-btn').length);
        console.log('- Alternative remove buttons:', document.querySelectorAll('.remove-btn').length);
        console.log('- Clear cart button:', !!document.getElementById('clear-cart-btn'));
        
        // Debug all cart items
        const cartItems = document.querySelectorAll('.cart-item');
        console.log(`📦 Found ${cartItems.length} cart items`);
        cartItems.forEach((item, index) => {
            console.log(`Cart item ${index}:`, {
                element: item,
                dataKey: item.dataset.key,
                removeBtn: item.querySelector('.remove-item-btn'),
                quantityInput: item.querySelector('.quantity-input')
            });
        });
        
        // Initialize quantity controls
        initQuantityControls();
        
        // Initialize remove buttons
        initRemoveButtons();
        
        // Initialize clear cart button
        initClearCartButton();
        
        // Add manual testing functions to window
        window.testRemoveFunction = function(key) {
            console.log('🧪 Testing remove function manually for key:', key);
            removeCartItem(key);
        };
        
        window.testClearFunction = function() {
            console.log('🧪 Testing clear function manually');
            clearCart();
        };
        
        console.log('✅ Cart page initialization complete');
        console.log('💡 You can test manually with: testRemoveFunction("key") or testClearFunction()');
    }, 100);
}

function initQuantityControls() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    
    console.log(`🔢 Found ${quantityInputs.length} quantity inputs and ${quantityBtns.length} quantity buttons`);
    
    quantityInputs.forEach(input => {
        console.log('🔗 Binding change event to input:', input.dataset.key);
        input.addEventListener('change', function() {
            console.log('📝 Quantity input changed:', this.dataset.key, this.value);
            updateCartItemQuantity(this.dataset.key, this.value);
        });
    });
    
    quantityBtns.forEach(btn => {
        console.log('🔗 Binding click event to button:', btn.dataset.action);
        btn.addEventListener('click', function() {
            console.log('🖱️ Quantity button clicked:', btn.dataset.action);
            const input = this.parentElement.querySelector('.quantity-input');
            const action = this.dataset.action;
            const currentValue = parseInt(input.value);
            
            if (action === 'increase') {
                input.value = currentValue + 1;
                console.log('➕ Increased quantity to:', input.value);
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
                console.log('➖ Decreased quantity to:', input.value);
            }
            
            updateCartItemQuantity(input.dataset.key, input.value);
        });
    });
}

function initRemoveButtons() {
    const removeButtons = document.querySelectorAll('.remove-item-btn');
    
    console.log(`🗑️ Found ${removeButtons.length} remove buttons`);
    console.log('Remove buttons:', removeButtons);
    
    // Also check for alternative selectors
    const removeButtonsAlt = document.querySelectorAll('.remove-btn');
    console.log(`🔍 Alternative remove buttons (.remove-btn): ${removeButtonsAlt.length}`);
    
    // Check for buttons with trash icons
    const trashButtons = document.querySelectorAll('button i.fa-trash');
    console.log(`🔍 Buttons with trash icons: ${trashButtons.length}`);
    
    removeButtons.forEach((btn, index) => {
        console.log(`🔗 Binding click event to remove button ${index}:`, {
            key: btn.dataset.key,
            name: btn.dataset.name,
            element: btn
        });
        
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            const key = this.dataset.key;
            const productName = this.dataset.name || 'sản phẩm';
            
            console.log('🖱️ Remove button clicked:', key, productName);
            
            if (confirm(`Bạn có chắc muốn xóa "${productName}" khỏi giỏ hàng?`)) {
                console.log('✅ User confirmed removal');
                removeCartItem(key);
            } else {
                console.log('❌ User cancelled removal');
            }
        });
    });
    
    // Also add event listeners to alternative buttons if they exist
    removeButtonsAlt.forEach((btn, index) => {
        if (!btn.classList.contains('remove-item-btn')) { // Avoid duplicate listeners
            console.log(`🔗 Binding click event to alternative remove button ${index}`);
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                const key = this.dataset.key || this.getAttribute('data-key');
                const productName = this.dataset.name || this.getAttribute('data-name') || 'sản phẩm';
                
                console.log('🖱️ Alternative remove button clicked:', key, productName);
                
                if (key && confirm(`Bạn có chắc muốn xóa "${productName}" khỏi giỏ hàng?`)) {
                    console.log('✅ User confirmed removal');
                    removeCartItem(key);
                } else if (!key) {
                    console.error('❌ No key found for remove button');
                } else {
                    console.log('❌ User cancelled removal');
                }
            });
        }
    });
}

function initClearCartButton() {
    const clearBtn = document.getElementById('clear-cart-btn');
    console.log('🧹 Clear cart button found:', !!clearBtn);
    console.log('Clear cart button element:', clearBtn);
    
    // Also check for alternative selectors
    const clearBtnAlt = document.querySelector('[id*="clear"]');
    console.log('🔍 Alternative clear button:', clearBtnAlt);
    
    if (clearBtn) {
        console.log('🔗 Binding click event to clear cart button');
        clearBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            console.log('🖱️ Clear cart button clicked');
            if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
                console.log('✅ User confirmed clear cart');
                clearCart();
            } else {
                console.log('❌ User cancelled clear cart');
            }
        });
    } else {
        console.warn('⚠️ Clear cart button not found!');
        
        // Try to find it by other methods
        const clearButtons = document.querySelectorAll('button');
        clearButtons.forEach((btn, index) => {
            const text = btn.textContent.toLowerCase();
            if (text.includes('xóa toàn bộ') || text.includes('clear') || text.includes('xóa hết')) {
                console.log(`🔍 Found potential clear button ${index}:`, btn);
            }
        });
    }
}

// Cart API Functions
async function updateCartItemQuantity(key, quantity) {
    console.log('🔄 Updating cart item quantity:', key, quantity);
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
        
        console.log('📡 API Response status:', response.status);
        const result = await response.json();
        console.log('📡 API Response data:', result);
        
        if (response.ok) {
            // Update cart summary
            updateCartSummary(result.cart_summary);
            
            // Update cart counter
            updateCartCounter(result.cart_count);
            
            // Show success notification
            showSimpleNotification('Đã cập nhật giỏ hàng!', 'success');
            console.log('✅ Cart update successful');
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('❌ Error updating cart:', error);
        showSimpleNotification('Có lỗi xảy ra khi cập nhật giỏ hàng!', 'error');
    }
}

async function removeCartItem(key) {
    console.log('🗑️ Starting removal of cart item:', key);
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
        
        console.log('📡 Remove API Response status:', response.status);
        const result = await response.json();
        console.log('📡 Remove API Response data:', result);
        
        if (response.ok) {
            console.log('✅ Item removed successfully from backend');
            
            // Remove item from DOM
            const cartItem = document.querySelector(`[data-key="${key}"]`).closest('.cart-item');
            if (cartItem) {
                console.log('🎬 Starting removal animation');
                cartItem.style.animation = 'slideOutLeft 0.3s ease';
                setTimeout(() => {
                    cartItem.remove();
                    console.log('🗑️ Item removed from DOM');
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    console.log('Remaining items:', remainingItems.length);
                    if (remainingItems.length === 0) {
                        console.log('🔄 Cart is empty, reloading page');
                        location.reload(); // Reload to show empty cart message
                    }
                }, 300);
            } else {
                console.error('❌ Could not find cart item in DOM');
            }
            
            // Update cart summary
            if (result.cart_summary) {
                updateCartSummary(result.cart_summary);
            }
            
            // Update cart counter
            if (result.cart_count !== undefined) {
                updateCartCounter(result.cart_count);
            }
            
            // Show success notification
            showSimpleNotification('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('❌ Error removing item:', error);
        showSimpleNotification('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
    }
}

async function clearCart() {
    console.log('🧹 Starting clear cart operation');
    try {
        const response = await fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        });
        
        console.log('📡 Clear cart API Response status:', response.status);
        const result = await response.json();
        console.log('📡 Clear cart API Response data:', result);
        
        if (response.ok) {
            console.log('✅ Cart cleared successfully');
            // Reload page to show empty cart
            location.reload();
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('❌ Error clearing cart:', error);
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
        shippingEl.innerHTML = summary.shipping === 0 ? '<span class="text-success">Miễn phí</span>' : formatPrice(summary.shipping);
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
    return new Intl.NumberFormat('vi-VN').format(price) + '₫';
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
            flyingElement.style.transform = `translate(${cartRect.left - rect.left}px, ${cartRect.top - rect.top}px) scale(0.3)`;
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

// Manual test functions for debugging
window.manualUpdateTest = function(key, quantity) {
    console.log('Manual update test:', key, quantity);
    if (typeof updateCartItemQuantity === 'function') {
        updateCartItemQuantity(key, quantity);
    } else {
        alert('updateCartItemQuantity function not found!');
    }
};

window.manualRemoveTest = function(key) {
    console.log('Manual remove test:', key);
    if (typeof removeCartItem === 'function') {
        removeCartItem(key);
    } else {
        alert('removeCartItem function not found!');
    }
};

window.manualClearTest = function() {
    console.log('Manual clear test');
    if (typeof clearCart === 'function') {
        clearCart();
    } else {
        alert('clearCart function not found!');
    }
};