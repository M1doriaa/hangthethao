// Hang The Thao - Product Detail JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeProductDetail();
});

function initializeProductDetail() {
    // Initialize image gallery
    initImageGallery();
    
    // Initialize product options
    initProductOptions();
    
    // Initialize quantity controls
    initQuantityControls();
    
    // Initialize cart functionality
    initCartFunctionality();
    
    // Initialize smooth scrolling
    initSmoothScrolling();
    
    // Initialize loading animations
    initLoadingAnimations();
}

// Image Gallery Functions
function initImageGallery() {
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.getElementById('mainImage');
    
    if (!mainImage) return;
    
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            const img = this.querySelector('img');
            if (img) {
                changeMainImage(this, img.src.replace('80x80', '500x600'));
            }
        });
        
        // Add keyboard navigation
        thumbnail.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        thumbnail.setAttribute('tabindex', '0');
        thumbnail.setAttribute('role', 'button');
        thumbnail.setAttribute('aria-label', `Xem ảnh ${index + 1}`);
    });
    
    // Add zoom functionality
    mainImage.addEventListener('click', function() {
        openImageModal(this.src);
    });
}

function changeMainImage(thumbnail, imageUrl) {
    const mainImage = document.getElementById('mainImage');
    if (!mainImage) return;
    
    // Add loading state
    mainImage.style.opacity = '0.5';
    
    // Remove active class from all thumbnails
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
        item.setAttribute('aria-selected', 'false');
    });
    
    // Add active class to clicked thumbnail
    thumbnail.classList.add('active');
    thumbnail.setAttribute('aria-selected', 'true');
    
    // Load new image
    const newImage = new Image();
    newImage.onload = function() {
        mainImage.src = this.src;
        mainImage.style.opacity = '1';
    };
    newImage.src = imageUrl;
}

function openImageModal(imageSrc) {
    // Create modal for image zoom
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <div class="image-modal-backdrop" onclick="closeImageModal()">
            <div class="image-modal-content">
                <img src="${imageSrc}" alt="Ảnh phóng to" class="img-fluid">
                <button class="image-modal-close" onclick="closeImageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    // Add ESC key listener
    document.addEventListener('keydown', handleModalEscape);
}

function closeImageModal() {
    const modal = document.querySelector('.image-modal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleModalEscape);
    }
}

function handleModalEscape(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
}

// Product Options Functions
function initProductOptions() {
    initSizeOptions();
    initColorOptions();
}

function initSizeOptions() {
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectSize(this);
        });
        
        option.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                selectSize(this);
            }
        });
        
        option.setAttribute('tabindex', '0');
        option.setAttribute('role', 'radio');
    });
}

function initColorOptions() {
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectColor(this);
        });
        
        option.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                selectColor(this);
            }
        });
        
        option.setAttribute('tabindex', '0');
        option.setAttribute('role', 'radio');
    });
}

function selectSize(element) {
    document.querySelectorAll('.size-option').forEach(option => {
        option.classList.remove('active');
        option.setAttribute('aria-checked', 'false');
    });
    element.classList.add('active');
    element.setAttribute('aria-checked', 'true');
    
    // Show size selection feedback
    showSelectionFeedback('Đã chọn kích thước: ' + element.textContent.trim());
}

function selectColor(element) {
    document.querySelectorAll('.color-option').forEach(option => {
        option.classList.remove('active');
        option.setAttribute('aria-checked', 'false');
    });
    element.classList.add('active');
    element.setAttribute('aria-checked', 'true');
    
    // Show color selection feedback
    showSelectionFeedback('Đã chọn màu: ' + element.textContent.trim());
}

function showSelectionFeedback(message) {
    // Create temporary feedback element
    const feedback = document.createElement('div');
    feedback.className = 'selection-feedback';
    feedback.textContent = message;
    feedback.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--primary-red);
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        z-index: 1000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(feedback);
    
    setTimeout(() => {
        feedback.remove();
    }, 2000);
}

// Quantity Controls
function initQuantityControls() {
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;
    
    quantityInput.addEventListener('input', function() {
        validateQuantity(this);
    });
    
    quantityInput.addEventListener('blur', function() {
        validateQuantity(this);
    });
}

function validateQuantity(input) {
    const min = parseInt(input.getAttribute('min')) || 1;
    const max = parseInt(input.getAttribute('max')) || 999;
    let value = parseInt(input.value) || min;
    
    if (value < min) value = min;
    if (value > max) value = max;
    
    input.value = value;
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;
    
    const max = parseInt(quantityInput.getAttribute('max')) || 999;
    const current = parseInt(quantityInput.value) || 1;
    
    if (current < max) {
        quantityInput.value = current + 1;
        updateTotalPrice();
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;
    
    const min = parseInt(quantityInput.getAttribute('min')) || 1;
    const current = parseInt(quantityInput.value) || 1;
    
    if (current > min) {
        quantityInput.value = current - 1;
        updateTotalPrice();
    }
}

function updateTotalPrice() {
    const quantityInput = document.getElementById('quantity');
    const priceElement = document.querySelector('.current-price');
    
    if (!quantityInput || !priceElement) return;
    
    const quantity = parseInt(quantityInput.value) || 1;
    const unitPrice = parseFloat(priceElement.textContent.replace(/[^\d]/g, ''));
    const totalPrice = unitPrice * quantity;
    
    // Update displayed total if element exists
    const totalElement = document.getElementById('total-price');
    if (totalElement) {
        totalElement.textContent = new Intl.NumberFormat('vi-VN').format(totalPrice) + '₫';
    }
}

// Cart Functionality
function initCartFunctionality() {
    // Setup CSRF token for AJAX requests
    setupCSRFToken();
    
    // Load cart count from server on page load
    updateCartCounterFromServer();
}

function setupCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.getAttribute('content');
    }
}

async function addToCart() {
    const productData = getProductData();
    if (!validateProductSelection(productData)) return;
    
    // Show loading state
    const addButton = document.querySelector('.btn-add-cart');
    const originalText = addButton.innerHTML;
    addButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...';
    addButton.disabled = true;
    
    try {
        // Send to Laravel backend
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(productData)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Show success notification with cart animation
            showCartNotification(productData, result.cart_count);
            
            // Update cart counter
            updateCartCounter(result.cart_count);
            
            // Add animation effect
            animateAddToCart();
            
        } else {
            throw new Error(result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng. Vui lòng thử lại!', 'error');
    } finally {
        // Restore button state
        addButton.innerHTML = originalText;
        addButton.disabled = false;
    }
}

function buyNow() {
    const productData = getProductData();
    if (!validateProductSelection(productData)) return;
    
    // Simulate buy now process
    showNotification('Chuyển hướng đến trang thanh toán...', 'info');
    
    // In real application, redirect to checkout
    setTimeout(() => {
        // window.location.href = '/checkout';
        console.log('Redirect to checkout with:', productData);
    }, 1500);
}

function getProductData() {
    const productTitle = document.querySelector('.product-title')?.textContent.trim();
    const selectedSize = document.querySelector('.size-option.active')?.textContent.trim();
    const selectedColor = document.querySelector('.color-option.active')?.textContent.trim();
    const quantity = parseInt(document.getElementById('quantity')?.value) || 1;
    const price = parseFloat(document.querySelector('.current-price')?.textContent.replace(/[^\d]/g, '')) || 0;
    const image = document.getElementById('mainImage')?.src;
    
    return {
        name: productTitle,
        size: selectedSize,
        color: selectedColor,
        quantity: quantity,
        price: price,
        image: image,
        id: window.location.pathname.split('/').pop()
    };
}

function validateProductSelection(productData) {
    if (!productData.size) {
        showNotification('Vui lòng chọn kích thước!', 'error');
        document.querySelector('.size-options')?.scrollIntoView({ behavior: 'smooth' });
        return false;
    }
    
    if (!productData.color) {
        showNotification('Vui lòng chọn màu sắc!', 'error');
        document.querySelector('.color-options')?.scrollIntoView({ behavior: 'smooth' });
        return false;
    }
    
    if (productData.quantity < 1) {
        showNotification('Số lượng không hợp lệ!', 'error');
        document.getElementById('quantity')?.focus();
        return false;
    }
    
    return true;
}

function addToCartStorage(productData) {
    let cart = JSON.parse(localStorage.getItem('hangthethao_cart')) || [];
    
    // Check if product already exists in cart
    const existingIndex = cart.findIndex(item => 
        item.id === productData.id && 
        item.size === productData.size && 
        item.color === productData.color
    );
    
    if (existingIndex > -1) {
        cart[existingIndex].quantity += productData.quantity;
    } else {
        cart.push(productData);
    }
    
    localStorage.setItem('hangthethao_cart', JSON.stringify(cart));
}

function loadCartFromStorage() {
    const cart = JSON.parse(localStorage.getItem('hangthethao_cart')) || [];
    return cart;
}

function updateCartCounter(serverCount = null) {
    let totalItems;
    
    if (serverCount !== null) {
        totalItems = serverCount;
    } else {
        const cart = loadCartFromStorage();
        totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    }
    
    const cartBadge = document.querySelector('.btn-danger .badge');
    if (cartBadge) {
        cartBadge.textContent = totalItems;
        
        // Add bounce animation
        cartBadge.style.animation = 'cartBounce 0.6s ease';
        setTimeout(() => {
            cartBadge.style.animation = '';
        }, 600);
    }
}

async function updateCartCounterFromServer() {
    try {
        const response = await fetch('/cart/count');
        if (response.ok) {
            const result = await response.json();
            updateCartCounter(result.cart_count);
        }
    } catch (error) {
        console.error('Error fetching cart count:', error);
    }
}

function showCartNotification(productData, cartCount) {
    // Create notification popup
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <div class="cart-notification-content">
            <div class="cart-notification-header">
                <i class="fas fa-check-circle text-success"></i>
                <span class="fw-bold">Đã thêm vào giỏ hàng!</span>
                <button class="btn-close" onclick="this.closest('.cart-notification').remove()"></button>
            </div>
            <div class="cart-notification-body">
                <div class="d-flex align-items-center">
                    <img src="${productData.image}" alt="${productData.name}" class="notification-product-image">
                    <div class="notification-product-info">
                        <h6 class="mb-1">${productData.name}</h6>
                        <p class="text-muted mb-0">
                            Kích thước: ${productData.size} | Màu: ${productData.color} | SL: ${productData.quantity}
                        </p>
                        <p class="text-danger fw-bold mb-0">${formatPrice(productData.price * productData.quantity)}</p>
                    </div>
                </div>
            </div>
            <div class="cart-notification-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Giỏ hàng: ${cartCount} sản phẩm</span>
                    <div>
                        <button class="btn btn-outline-primary btn-sm me-2" onclick="this.closest('.cart-notification').remove()">
                            Tiếp tục mua
                        </button>
                        <a href="/cart" class="btn btn-primary btn-sm">
                            Xem giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        width: 400px;
        max-width: 90vw;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        border: 1px solid #e0e0e0;
        animation: slideInRight 0.4s ease;
        overflow: hidden;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.4s ease';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 400);
        }
    }, 5000);
    
    // Add product fly animation
    animateProductToCart(productData.image);
}

function animateProductToCart(imageSrc) {
    const mainImage = document.getElementById('mainImage');
    const cartIcon = document.querySelector('.btn-danger');
    
    if (!mainImage || !cartIcon) return;
    
    // Create flying image
    const flyingImage = document.createElement('img');
    flyingImage.src = imageSrc;
    flyingImage.style.cssText = `
        position: fixed;
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        z-index: 1100;
        pointer-events: none;
        transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    `;
    
    // Get positions
    const imageRect = mainImage.getBoundingClientRect();
    const cartRect = cartIcon.getBoundingClientRect();
    
    // Set initial position
    flyingImage.style.left = imageRect.left + 'px';
    flyingImage.style.top = imageRect.top + 'px';
    
    document.body.appendChild(flyingImage);
    
    // Animate to cart
    setTimeout(() => {
        flyingImage.style.left = cartRect.left + 'px';
        flyingImage.style.top = cartRect.top + 'px';
        flyingImage.style.transform = 'scale(0.3)';
        flyingImage.style.opacity = '0.8';
    }, 100);
    
    // Remove flying image
    setTimeout(() => {
        if (flyingImage.parentElement) {
            flyingImage.remove();
        }
    }, 900);
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

function updateCartDisplay() {
    updateCartCounter();
}

function animateAddToCart() {
    const addButton = document.querySelector('.btn-add-cart');
    if (addButton) {
        addButton.style.transform = 'scale(0.95)';
        setTimeout(() => {
            addButton.style.transform = 'scale(1)';
        }, 150);
    }
}

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
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
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 4000);
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}

// Smooth Scrolling
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Loading Animations
function initLoadingAnimations() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        if (!img.complete) {
            img.style.opacity = '0';
            img.addEventListener('load', function() {
                this.style.transition = 'opacity 0.3s ease';
                this.style.opacity = '1';
            });
        }
    });
}

// Quick add to cart for related products
function addToCartQuick(productId) {
    showNotification(`Đã thêm sản phẩm #${productId} vào giỏ hàng!`, 'success');
    updateCartCounter();
    
    // Add animation to the button
    const button = event.target.closest('button');
    if (button) {
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-danger');
        
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-cart-plus"></i>';
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-danger');
        }, 1500);
    }
}

// Accessibility enhancements
function enhanceAccessibility() {
    // Add ARIA labels and roles
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach((option, index) => {
        option.setAttribute('role', 'radio');
        option.setAttribute('aria-label', `Kích thước ${option.textContent.trim()}`);
    });
    
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach((option, index) => {
        option.setAttribute('role', 'radio');
        option.setAttribute('aria-label', `Màu ${option.textContent.trim()}`);
    });
    
    // Add focus indicators
    const focusableElements = document.querySelectorAll('.size-option, .color-option, .thumbnail-item');
    focusableElements.forEach(element => {
        element.addEventListener('focus', function() {
            this.style.outline = '2px solid var(--primary-red)';
            this.style.outlineOffset = '2px';
        });
        
        element.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });
}
