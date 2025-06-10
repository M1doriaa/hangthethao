@extends('layouts.app')

@section('title', ($query ? 'Tìm kiếm: ' . $query : 'Tìm kiếm sản phẩm') . ' - Hang The Thao')

@section('content')
<!-- Search Banner -->
<section class="search-banner bg-gradient-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2">
                    <i class="fas fa-search me-2"></i>
                    @if($query)
                        Kết quả tìm kiếm cho "{{ $query }}"
                    @else
                        Tìm kiếm sản phẩm
                    @endif
                </h1>
                @if($query && count($products) > 0)
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-check-circle me-1"></i>
                        Tìm thấy {{ count($products) }} sản phẩm phù hợp
                    </p>
                @elseif($query)
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Không tìm thấy sản phẩm nào
                    </p>
                @endif
            </div>
            <div class="col-md-4 text-md-end">
                <small class="opacity-75">
                    <i class="fas fa-home me-1"></i>
                    <a href="{{ route('home') }}" class="text-white text-decoration-none">Trang chủ</a>
                    <i class="fas fa-chevron-right mx-2"></i>
                    Tìm kiếm
                </small>
            </div>
        </div>
    </div>
</section>

<!-- Search Form & Filters -->
<section class="search-controls py-4 bg-white border-bottom">
    <div class="container">
        <form method="GET" action="{{ route('search') }}" class="search-form">
            <div class="row g-3 align-items-center">
                <!-- Search Input -->
                <div class="col-lg-5 col-md-6">
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control form-control-lg ps-5" 
                               name="q" 
                               placeholder="Nhập từ khóa tìm kiếm..." 
                               value="{{ $query }}"
                               autocomplete="off">
                        <i class="fas fa-search position-absolute start-0 top-50 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="col-lg-3 col-md-3">
                    <select class="form-select form-select-lg" name="category">
                        <option value="all">Tất cả danh mục</option>
                        <option value="ao-clb" {{ $category == 'ao-clb' ? 'selected' : '' }}>
                            Áo CLB
                        </option>
                        <option value="ao-doi-tuyen" {{ $category == 'ao-doi-tuyen' ? 'selected' : '' }}>
                            Áo Đội Tuyển
                        </option>
                        <option value="phu-kien" {{ $category == 'phu-kien' ? 'selected' : '' }}>
                            Phụ Kiện
                        </option>
                    </select>
                </div>
                
                <!-- Search Button -->
                <div class="col-lg-2 col-md-3">
                    <button class="btn btn-primary btn-lg w-100" type="submit">
                        <i class="fas fa-search me-2"></i>Tìm kiếm
                    </button>
                </div>
                
                <!-- View Controls -->
                @if(count($products) > 0)
                <div class="col-lg-2">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary active" id="grid-view-btn" title="Xem dạng lưới">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="list-view-btn" title="Xem dạng danh sách">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- Search Filters & Sort -->
@if(count($products) > 0)
<section class="search-filters py-3 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <!-- Price Filters -->
                    <div class="filter-group">
                        <label class="form-label small text-muted mb-1">Giá:</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="priceFilter" id="price-all" value="all" checked>
                            <label class="btn btn-outline-secondary btn-sm" for="price-all">Tất cả</label>
                            
                            <input type="radio" class="btn-check" name="priceFilter" id="price-under-500k" value="under-500k">
                            <label class="btn btn-outline-secondary btn-sm" for="price-under-500k">< 500k</label>
                            
                            <input type="radio" class="btn-check" name="priceFilter" id="price-500k-1m" value="500k-1m">
                            <label class="btn btn-outline-secondary btn-sm" for="price-500k-1m">500k-1M</label>
                            
                            <input type="radio" class="btn-check" name="priceFilter" id="price-over-1m" value="over-1m">
                            <label class="btn btn-outline-secondary btn-sm" for="price-over-1m">> 1M</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end align-items-center gap-3">
                    <!-- Sort Options -->
                    <div class="sort-wrapper">
                        <label class="form-label small text-muted mb-1 d-block">Sắp xếp:</label>
                        <select class="form-select form-select-sm sort-select" id="sort-select">
                            <option value="relevance">Liên quan nhất</option>
                            <option value="name">Tên A-Z</option>
                            <option value="price-low">Giá thấp → cao</option>
                            <option value="price-high">Giá cao → thấp</option>
                            <option value="rating">Đánh giá cao nhất</option>
                        </select>
                    </div>
                    
                    <!-- Results Count -->
                    <div class="results-count text-center">
                        <small class="text-muted d-block">Tìm thấy</small>
                        <strong class="text-primary">{{ count($products) }}</strong>
                        <small class="text-muted d-block">sản phẩm</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Products Section -->
<section class="products-section py-4">
    <div class="container">
        @if(count($products) > 0)
        <!-- Products Grid -->
        <div class="row g-4" id="products-grid">
            @foreach($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 product-item" 
                 data-name="{{ strtolower($product['name']) }}" 
                 data-price="{{ $product['price'] }}" 
                 data-rating="{{ $product['rating'] }}">
                <div class="product-card card border-0 h-100 shadow-sm">
                    <!-- Product Image -->
                    <div class="product-image-wrapper position-relative overflow-hidden">
                        <a href="{{ route('product.show', $product['id']) }}" class="d-block">
                            <img src="{{ $product['image'] }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $product['name'] }}"
                                 style="height: 250px; object-fit: cover;">
                        </a>
                          <!-- Hover Overlay -->
                        <div class="product-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white">
                                <i class="fas fa-eye fa-2x mb-2"></i>
                                <div class="fw-bold">Xem chi tiết sản phẩm</div>
                            </div>
                        </div>
                        
                        <!-- Category Badge -->
                        <span class="badge category-badge position-absolute top-0 start-0 m-2">
                            @if($product['id'] >= 100 && $product['id'] < 200)
                                <i class="fas fa-tshirt me-1"></i>Áo CLB
                            @elseif($product['id'] >= 200 && $product['id'] < 300)
                                <i class="fas fa-flag me-1"></i>Áo Đội Tuyển
                            @else
                                <i class="fas fa-football-ball me-1"></i>Phụ Kiện
                            @endif
                        </span>

                        <!-- Stock Status -->
                        @if($product['stock_quantity'] > 0)
                            <span class="badge stock-badge bg-success position-absolute top-0 end-0 m-2">
                                <i class="fas fa-check me-1"></i>Còn hàng
                            </span>
                        @else
                            <span class="badge stock-badge bg-danger position-absolute top-0 end-0 m-2">
                                <i class="fas fa-times me-1"></i>Hết hàng
                            </span>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="card-body p-3">
                        <div class="product-category text-uppercase small text-muted mb-2">
                            @if($product['id'] >= 100 && $product['id'] < 200)
                                Áo Câu Lạc Bộ
                            @elseif($product['id'] >= 200 && $product['id'] < 300)
                                Áo Đội Tuyển Quốc Gia
                            @else
                                Phụ Kiện Thể Thao
                            @endif
                        </div>
                        
                        <h6 class="product-title card-title mb-2">
                            <a href="{{ route('product.show', $product['id']) }}" 
                               class="text-decoration-none text-dark">
                                {{ $product['name'] }}
                            </a>
                        </h6>
                        
                        <!-- Rating -->
                        <div class="product-rating mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $product['rating'])
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                            <small class="text-muted ms-1">({{ $product['rating'] }}/5)</small>
                        </div>
                        
                        <!-- Price -->
                        <div class="product-price mb-3">
                            <span class="price-current text-primary fw-bold h6 mb-0">
                                {{ number_format($product['price'], 0, ',', '.') }}₫
                            </span>
                            @if(isset($product['old_price']))
                                <span class="price-old text-muted text-decoration-line-through ms-2">
                                    {{ number_format($product['old_price'], 0, ',', '.') }}₫
                                </span>
                            @endif
                        </div>
                          <!-- Quick Info -->
                        <div class="product-info small text-muted">
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-palette me-1"></i>Nhiều màu</span>
                                <span><i class="fas fa-expand-arrows-alt me-1"></i>Size S-XXL</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty Search Results -->
        <div class="empty-state text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search fa-4x text-muted opacity-50"></i>
            </div>
            <h3 class="h4 text-muted mb-3">Không tìm thấy sản phẩm nào</h3>
            @if($query)
            <p class="text-muted mb-4">
                Không có sản phẩm nào phù hợp với từ khóa 
                <strong class="text-primary">"{{ $query }}"</strong>
                @if($category != 'all')
                    trong danh mục 
                    <strong class="text-primary">
                        @if($category == 'ao-clb') Áo CLB
                        @elseif($category == 'ao-doi-tuyen') Áo Đội Tuyển  
                        @else Phụ Kiện
                        @endif
                    </strong>
                @endif
            </p>
            @else
            <p class="text-muted mb-4">
                Nhập từ khóa để tìm kiếm sản phẩm trong cửa hàng của chúng tôi
            </p>
            @endif
            
            <!-- Search Suggestions -->
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="card-title text-start mb-3">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                Gợi ý tìm kiếm:
                            </h6>
                            <ul class="list-unstyled text-start mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Kiểm tra chính tả của từ khóa
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Thử sử dụng từ khóa khác hoặc tổng quát hơn
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Tìm kiếm theo tên đội bóng hoặc thương hiệu
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Duyệt theo danh mục sản phẩm
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-4 d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Về trang chủ
                </a>
                <a href="{{ route('category.index', 'ao-clb') }}" class="btn btn-outline-primary">
                    <i class="fas fa-tshirt me-2"></i>Xem áo CLB
                </a>
                <a href="{{ route('category.index', 'ao-doi-tuyen') }}" class="btn btn-outline-primary">
                    <i class="fas fa-flag me-2"></i>Xem áo đội tuyển
                </a>
            </div>
        </div>
        @endif

        <!-- Popular Categories (only show if we have results) -->
        @if(count($products) > 0)
        <section class="popular-categories mt-5 pt-5 border-top">
            <div class="text-center mb-4">
                <h4 class="mb-2">Danh mục phổ biến</h4>
                <p class="text-muted">Khám phá thêm các sản phẩm hot nhất</p>
            </div>
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="category-card card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="category-icon mb-3">
                                <i class="fas fa-tshirt fa-3x text-primary"></i>
                            </div>
                            <h6 class="card-title">Áo CLB</h6>
                            <p class="text-muted small mb-3">Áo đấu các câu lạc bộ hàng đầu thế giới</p>
                            <a href="{{ route('category.index', 'ao-clb') }}" class="btn btn-outline-primary btn-sm stretched-link">
                                <i class="fas fa-arrow-right me-1"></i>Xem ngay
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="category-icon mb-3">
                                <i class="fas fa-flag fa-3x text-primary"></i>
                            </div>
                            <h6 class="card-title">Áo Đội Tuyển</h6>
                            <p class="text-muted small mb-3">Áo đấu các đội tuyển quốc gia</p>
                            <a href="{{ route('category.index', 'ao-doi-tuyen') }}" class="btn btn-outline-primary btn-sm stretched-link">
                                <i class="fas fa-arrow-right me-1"></i>Xem ngay
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="category-icon mb-3">
                                <i class="fas fa-football-ball fa-3x text-primary"></i>
                            </div>
                            <h6 class="card-title">Phụ Kiện</h6>
                            <p class="text-muted small mb-3">Phụ kiện thể thao chất lượng cao</p>
                            <a href="{{ route('category.index', 'phu-kien') }}" class="btn btn-outline-primary btn-sm stretched-link">
                                <i class="fas fa-arrow-right me-1"></i>Xem ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
/* Modern Search Page Styling */
.bg-gradient-primary {
    background: linear-gradient(135deg, #C41E3A 0%, #a01729 100%);
}

/* Search Banner */
.search-banner {
    background-attachment: fixed;
    background-size: cover;
    position: relative;
}

.search-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.1);
}

.search-banner .container {
    position: relative;
    z-index: 2;
}

/* Search Controls */
.search-controls {
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.search-form .form-control:focus,
.search-form .form-select:focus {
    border-color: #C41E3A;
    box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.15);
}

/* Search Filters */
.search-filters {
    background: #f8f9fa !important;
}

.filter-group .btn-check:checked + .btn {
    background-color: #C41E3A;
    border-color: #C41E3A;
    color: white;
}

.results-count {
    min-width: 80px;
}

/* Product Cards Enhanced */
.product-card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    background: white;
    border: 1px solid rgba(0,0,0,0.05);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    border-color: rgba(196, 30, 58, 0.2);
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
}

.product-overlay {
    background: linear-gradient(135deg, rgba(196, 30, 58, 0.9) 0%, rgba(160, 23, 41, 0.9) 100%);
    opacity: 0;
    transition: all 0.3s ease;
    backdrop-filter: blur(2px);
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-image {
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

/* Badges */
.category-badge {
    background: linear-gradient(135deg, #C41E3A 0%, #a01729 100%);
    border: 2px solid white;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.stock-badge {
    border: 2px solid white;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Product Info */
.product-title a {
    color: #2c3e50;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: #C41E3A;
}

.price-current {
    font-size: 1.25rem;
    font-weight: 700;
    color: #C41E3A;
}

/* Empty State */
.empty-state {
    padding: 3rem 0;
}

.empty-state .fas {
    color: #dee2e6;
}

/* Category Cards */
.category-card {
    transition: all 0.3s ease;
    position: relative;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.category-icon {
    transition: transform 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}

.category-card:hover .category-icon i {
    color: #C41E3A !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-banner {
        background-attachment: scroll;
    }
    
    .search-controls .row {
        gap: 1rem;
    }
    
    .search-filters .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-group .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .results-count {
        order: -1;
        text-align: center;
        padding: 0.5rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
}

@media (max-width: 576px) {
    .search-banner .h3 {
        font-size: 1.5rem;
    }
    
    .product-card {
        margin-bottom: 1rem;
    }
    
    .category-card .card-body {
        padding: 2rem 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initSearchPage();
});

function initSearchPage() {
    initSearchForm();
    initFilters();
    initSorting();
    initViewToggle();
    initProductCards();
    initKeyboardShortcuts();
}

function initSearchForm() {
    const searchForm = document.querySelector('.search-form');
    const searchInput = searchForm?.querySelector('input[name="q"]');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput?.value.trim();
            if (!query) {
                e.preventDefault();
                searchInput?.focus();
                showToast('Vui lòng nhập từ khóa tìm kiếm', 'warning');
            } else {
                showLoadingState();
            }
        });
    }
    
    // Auto-focus search input if no query
    if (searchInput && !searchInput.value) {
        setTimeout(() => searchInput.focus(), 100);
    }
}

function initFilters() {
    const priceFilters = document.querySelectorAll('input[name="priceFilter"]');
    
    priceFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            filterProducts();
        });
    });
}

function filterProducts() {
    const selectedPrice = document.querySelector('input[name="priceFilter"]:checked')?.value;
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const price = parseInt(product.dataset.price);
        let show = true;
        
        switch(selectedPrice) {
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
        }
        
        if (show) {
            product.style.display = 'block';
            product.style.animation = 'fadeIn 0.3s ease';
        } else {
            product.style.display = 'none';
        }
    });
    
    updateResultsCount();
}

function initSorting() {
    const sortSelect = document.getElementById('sort-select');
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortProducts(this.value);
        });
    }
}

function sortProducts(sortBy) {
    const container = document.getElementById('products-grid');
    const products = Array.from(container.children);
    
    showLoadingState();
    
    setTimeout(() => {
        products.sort((a, b) => {
            switch(sortBy) {
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name, 'vi');
                case 'price-low':
                    return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                case 'price-high':
                    return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                case 'rating':
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                default:
                    return 0;
            }
        });
        
        products.forEach(product => {
            container.appendChild(product);
        });
        
        hideLoadingState();
    }, 500);
}

function initViewToggle() {
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    
    if (gridBtn && listBtn) {
        gridBtn.addEventListener('click', () => setViewMode('grid'));
        listBtn.addEventListener('click', () => setViewMode('list'));
        
        // Load saved preference
        const savedView = localStorage.getItem('searchViewMode') || 'grid';
        setViewMode(savedView);
    }
}

function setViewMode(mode) {
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    const container = document.getElementById('products-grid');
    const products = container?.querySelectorAll('.product-item');
    
    if (!gridBtn || !listBtn || !container) return;
    
    // Update button states
    if (mode === 'grid') {
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        
        products?.forEach(item => {
            item.className = 'col-xl-3 col-lg-4 col-md-6 col-sm-6 product-item';
        });
    } else {
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
        
        products?.forEach(item => {
            item.className = 'col-12 product-item';
        });
    }
    
    localStorage.setItem('searchViewMode', mode);
}

function initProductCards() {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        // Add keyboard navigation
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const link = this.querySelector('a');
                if (link) link.click();
            }
        });
    });
}

function initKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('input[name="q"]')?.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput && document.activeElement === searchInput) {
                searchInput.value = '';
                searchInput.blur();
            }
        }
    });
}

function updateResultsCount() {
    const visibleProducts = document.querySelectorAll('.product-item[style*="block"], .product-item:not([style*="none"])').length;
    const resultsCountEl = document.querySelector('.results-count strong');
    
    if (resultsCountEl) {
        resultsCountEl.textContent = visibleProducts;
    }
}

function showLoadingState() {
    const container = document.getElementById('products-grid');
    if (container) {
        container.style.opacity = '0.6';
        container.style.pointerEvents = 'none';
        container.classList.add('loading');
    }
}

function hideLoadingState() {
    const container = document.getElementById('products-grid');
    if (container) {
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';
        container.classList.remove('loading');
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endpush