@extends('layouts.app')

@section('title', $categoryName . ' - Hang The Thao')

@section('content')
<!-- Category Header -->
<section class="category-header py-5" style="background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-white-50">
                                <i class="fas fa-home me-1"></i>Trang chủ
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">
                            {{ $categoryName }}
                        </li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold text-white mb-3">{{ $categoryName }}</h1>
                <p class="lead text-white-75 mb-0">
                    @if($category == 'ao-clb')
                        Bộ sưu tập áo đấu chính thức từ các câu lạc bộ hàng đầu thế giới
                    @elseif($category == 'ao-doi-tuyen')
                        Áo đấu chính thức các đội tuyển quốc gia tham dự các giải đấu lớn
                    @else
                        Phụ kiện thể thao chất lượng cao từ các thương hiệu uy tín
                    @endif
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="category-icon">
                    @if($category == 'ao-clb')
                        <i class="fas fa-shirt fa-5x text-white opacity-75"></i>
                    @elseif($category == 'ao-doi-tuyen')
                        <i class="fas fa-flag fa-5x text-white opacity-75"></i>
                    @else
                        <i class="fas fa-shopping-bag fa-5x text-white opacity-75"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Sort Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Hiển thị {{ count($products) }} sản phẩm</span>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="view-mode" id="grid-view" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="grid-view">
                            <i class="fas fa-th"></i>
                        </label>
                        <input type="radio" class="btn-check" name="view-mode" id="list-view">
                        <label class="btn btn-outline-secondary btn-sm" for="list-view">
                            <i class="fas fa-list"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Price Filter -->
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Lọc theo giá:</span>
                    <select class="form-select form-select-sm" id="price-filter">
                        <option value="all">Tất cả</option>
                        <option value="under-500k">Dưới 500k</option>
                        <option value="500k-1m">500k - 1M</option>
                        <option value="over-1m">Trên 1M</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex justify-content-end align-items-center">
                    <span class="text-muted me-3">Sắp xếp theo:</span>
                    <select class="form-select form-select-sm" style="width: auto;" id="sort-select">
                        <option value="name">Tên A-Z</option>
                        <option value="price-low">Giá thấp đến cao</option>
                        <option value="price-high">Giá cao đến thấp</option>
                        <option value="rating">Đánh giá cao nhất</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4" id="products-grid">
            @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 product-item" 
                 data-name="{{ strtolower($product['name']) }}" 
                 data-price="{{ $product['price'] }}" 
                 data-rating="{{ $product['rating'] }}">
                <div class="product-card card h-100 shadow-sm">
                    <!-- Product Image -->
                    <div class="position-relative overflow-hidden">
                        <a href="{{ route('product.show', $product['id']) }}">
                            <img src="{{ $product['image'] }}" 
                                 class="card-img-top" 
                                 alt="{{ $product['name'] }}"
                                 style="height: 250px; object-fit: cover; transition: transform 0.3s;">
                        </a>
                        
                        <!-- Stock Badge -->
                        @if($product['stock_quantity'] > 0)
                            <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                <i class="fas fa-check me-1"></i>Còn hàng
                            </span>
                        @else
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                <i class="fas fa-times me-1"></i>Hết hàng
                            </span>
                        @endif

                        <!-- Quick Actions -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <button class="btn btn-light btn-sm rounded-circle me-1" 
                                    title="Yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="btn btn-light btn-sm rounded-circle" 
                                    title="Xem nhanh">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">
                            <a href="{{ route('product.show', $product['id']) }}" 
                               class="text-decoration-none text-dark">
                                {{ $product['name'] }}
                            </a>
                        </h6>
                        
                        <!-- Rating -->
                        <div class="rating mb-2">
                            @for($i = 0; $i < $product['rating']; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                            @for($i = $product['rating']; $i < 5; $i++)
                                <i class="far fa-star text-warning"></i>
                            @endfor
                            <span class="text-muted ms-1">({{ $product['rating'] }}/5)</span>
                        </div>

                        <!-- Price -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h6 text-primary fw-bold mb-0">
                                    {{ number_format($product['price']) }}₫
                                </span>
                            </div>
                            <small class="text-muted">
                                Còn {{ $product['stock_quantity'] }} sản phẩm
                            </small>
                        </div>                        <!-- Description -->
                        <p class="text-muted small mt-2 mb-3">
                            {{ $product['description'] }}
                        </p>
                        
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if(count($products) == 0)
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-box-open fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">Không có sản phẩm nào</h4>
            <p class="text-muted">Hiện tại chưa có sản phẩm nào trong danh mục này.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-1"></i>Quay về trang chủ
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Related Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Danh mục khác</h3>
        <div class="row g-4">
            @if($category != 'ao-clb')
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-shirt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Áo CLB</h5>
                        <p class="text-muted">Áo đấu các câu lạc bộ hàng đầu</p>
                        <a href="{{ route('category.index', 'ao-clb') }}" class="btn btn-outline-primary">
                            Xem ngay
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($category != 'ao-doi-tuyen')
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-flag fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Áo Đội Tuyển</h5>
                        <p class="text-muted">Áo đấu các đội tuyển quốc gia</p>
                        <a href="{{ route('category.index', 'ao-doi-tuyen') }}" class="btn btn-outline-primary">
                            Xem ngay
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($category != 'phu-kien')
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-shopping-bag fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Phụ Kiện</h5>
                        <p class="text-muted">Phụ kiện thể thao chất lượng</p>
                        <a href="{{ route('category.index', 'phu-kien') }}" class="btn btn-outline-primary">
                            Xem ngay
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.product-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.product-card img:hover {
    transform: scale(1.05);
}

.view-details-hint {
    opacity: 0;
    transition: opacity 0.3s ease;
    margin-bottom: 0.5rem;
}

.product-card:hover .view-details-hint {
    opacity: 1;
}

.category-header {
    position: relative;
    overflow: hidden;
}

.category-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="50" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.rating .fas, .rating .far {
    font-size: 0.875rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.5);
}

@media (max-width: 768px) {
    .category-header .display-5 {
        font-size: 2rem;
    }
    
    .col-sm-6:nth-child(odd) {
        padding-right: 0.5rem;
    }
    
    .col-sm-6:nth-child(even) {
        padding-left: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort functionality
    const sortSelect = document.getElementById('sort-select');
    const productsGrid = document.getElementById('products-grid');
    const priceFilter = document.getElementById('price-filter');
    
    function filterAndSortProducts() {
        const sortBy = sortSelect.value;
        const priceRange = priceFilter.value;
        const products = Array.from(productsGrid.children);
        
        // Filter by price
        products.forEach(product => {
            const price = parseInt(product.dataset.price);
            let show = true;
            
            switch(priceRange) {
                case 'under-500k':
                    show = price < 500000;
                    break;
                case '500k-1m':
                    show = price >= 500000 && price <= 1000000;
                    break;
                case 'over-1m':
                    show = price > 1000000;
                    break;
                case 'all':
                default:
                    show = true;
                    break;
            }
            
            product.style.display = show ? 'block' : 'none';
        });
        
        // Get visible products for sorting
        const visibleProducts = products.filter(product => product.style.display !== 'none');
        
        // Sort visible products
        visibleProducts.sort((a, b) => {
            switch(sortBy) {
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'price-low':
                    return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                case 'price-high':
                    return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                case 'rating':
                    return parseInt(b.dataset.rating) - parseInt(a.dataset.rating);
                default:
                    return 0;
            }
        });
        
        // Clear grid and re-append sorted visible products
        productsGrid.innerHTML = '';
        visibleProducts.forEach(product => productsGrid.appendChild(product));
        
        // Append hidden products at the end
        products.filter(product => product.style.display === 'none')
               .forEach(product => productsGrid.appendChild(product));
        
        // Update product count
        const countElement = document.querySelector('.text-muted');
        if (countElement) {
            countElement.textContent = `Hiển thị ${visibleProducts.length} sản phẩm`;
        }
    }
    
    sortSelect.addEventListener('change', filterAndSortProducts);
    priceFilter.addEventListener('change', filterAndSortProducts);
    
    // View mode toggle
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    
    listViewBtn.addEventListener('change', function() {
        if (this.checked) {
            productsGrid.classList.add('list-view');
            // Change grid classes for list view
            const items = productsGrid.querySelectorAll('.product-item');
            items.forEach(item => {
                item.className = 'col-12 product-item';
            });
        }
    });
    
    gridViewBtn.addEventListener('change', function() {
        if (this.checked) {
            productsGrid.classList.remove('list-view');
            // Restore grid classes
            const items = productsGrid.querySelectorAll('.product-item');
            items.forEach(item => {
                item.className = 'col-lg-3 col-md-4 col-sm-6 product-item';
            });
        }
    });
});
</script>
@endpush
