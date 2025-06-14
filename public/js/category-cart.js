// Category Page Cart Functions for Hang The Thao
// Xử lý thêm sản phẩm vào giỏ hàng từ trang danh mục

document.addEventListener('DOMContentLoaded', function() {
    // Ensure cart.js functions are available
    if (typeof updateCartCounterFromServer === 'function') {
        console.log('Category cart functions initialized');
    }
});

// Add to cart function for category pages
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
            // Update cart counter if function exists
            if (typeof updateCartCounterFromServer === 'function') {
                updateCartCounterFromServer();
            }
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
    flyingElement.style.position = 'fixed';
    flyingElement.style.left = rect.left + 'px';
    flyingElement.style.top = rect.top + 'px';
    flyingElement.style.width = rect.width + 'px';
    flyingElement.style.height = rect.height + 'px';
    flyingElement.style.zIndex = '1001';
    flyingElement.style.pointerEvents = 'none';
    flyingElement.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    
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
