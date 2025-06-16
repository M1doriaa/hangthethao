@extends('admin.layout')

@section('title', 'Thêm sản phẩm mới')

@push('styles')
<style>
.main-image-preview, .additional-images-preview {
    margin-top: 15px;
}

.additional-image-item {
    margin-bottom: 10px;
}

.additional-image-item img {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.remove-main-image, .remove-additional-image {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.image-upload-area {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: border-color 0.3s ease;
}

.image-upload-area:hover {
    border-color: #C41E3A;
}

.image-upload-area.dragover {
    border-color: #C41E3A;
    background-color: #fdf2f2;
}

#main_image, #additional_images {
    margin-bottom: 10px;
}

.upload-progress {
    display: none;
    margin-top: 10px;
}

.file-info {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 8px;
    margin-top: 5px;
    font-size: 12px;
}

.upload-content {
    transition: all 0.3s ease;
}

.upload-content.uploading {
    opacity: 0.7;
    pointer-events: none;
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    margin-top: 15px;
}
</style>
@endpush

@section('content')
<!-- Success Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm mới
    </h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Main Product Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="sku" class="form-label">Mã SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" value="{{ old('sku') }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mã định danh duy nhất cho sản phẩm</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control price-input @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" 
                                       placeholder="0" required data-original-value="">
                                <span class="input-group-text">₫</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nhập giá không có dấu phẩy, hệ thống sẽ tự động format</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="original_price" class="form-label">Giá gốc (để tính giảm giá)</label>
                            <div class="input-group">
                                <input type="text" class="form-control price-input @error('original_price') is-invalid @enderror" 
                                       id="original_price" name="original_price" value="{{ old('original_price') }}" 
                                       placeholder="0" data-original-value="">
                                <span class="input-group-text">₫</span>
                            </div>
                            @error('original_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống nếu không có giảm giá</div>
                        </div>
                    </div>                    <div class="row">                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ old('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="brand" class="form-label">Thương hiệu</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                   id="brand" name="brand" value="{{ old('brand') }}" 
                                   placeholder="Nike, Adidas, Puma...">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>                    </div>

                    <!-- Product Type Section -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-cogs me-2"></i>Loại sản phẩm
                        </h6>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_variants">
                                    <strong>Sản phẩm có nhiều biến thể (size, màu sắc, giá khác nhau)</strong>
                                </label>
                            </div>
                            <div class="form-text">Bật tùy chọn này nếu sản phẩm có nhiều size/màu với giá khác nhau</div>
                        </div>

                        <!-- Simple Product Section -->
                        <div id="simple-product-section" style="display: {{ old('has_variants') ? 'none' : 'block' }};">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Sản phẩm đơn giản:</strong> Sử dụng giá cố định ở trên và có thể thêm size/màu sắc tùy chọn.
                            </div>
                        </div>

                        <!-- Variants Section -->
                        <div id="variants-section" style="display: {{ old('has_variants') ? 'block' : 'none' }};">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Lưu ý:</strong> Khi bật chế độ variants, giá sản phẩm sẽ được quản lý theo từng biến thể bên dưới.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Các size có sẵn</label>
                                    <div class="size-options">
                                        <div class="row">
                                            @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                            <div class="col-md-4 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input variant-size" type="checkbox" 
                                                           id="size_{{ $size }}" value="{{ $size }}">
                                                    <label class="form-check-label" for="size_{{ $size }}">{{ $size }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Các màu có sẵn</label>
                                    <div class="color-options">
                                        <div class="row">
                                            @php
                                            $colors = [
                                                'Đỏ' => '#FF0000',
                                                'Xanh dương' => '#0000FF', 
                                                'Xanh lá' => '#00FF00',
                                                'Vàng' => '#FFFF00',
                                                'Đen' => '#000000',
                                                'Trắng' => '#FFFFFF',
                                                'Xám' => '#808080',
                                                'Cam' => '#FFA500'
                                            ];
                                            @endphp
                                            @foreach($colors as $colorName => $colorCode)
                                            <div class="col-md-6 col-12 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input variant-color" type="checkbox" 
                                                           id="color_{{ $loop->index }}" value="{{ $colorName }}" 
                                                           data-color-code="{{ $colorCode }}">
                                                    <label class="form-check-label d-flex align-items-center" for="color_{{ $loop->index }}">
                                                        <span class="color-preview me-2" 
                                                              style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $colorCode }}; border: 1px solid #ddd;"></span>
                                                        {{ $colorName }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" id="generate-variants-btn">
                                    <i class="fas fa-magic me-2"></i>Tạo biến thể
                                </button>
                            </div>

                            <!-- Variants Table -->
                            <div id="variants-table-container" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Biến thể sản phẩm</h6>
                                    <div class="bulk-actions" style="display: none;">
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="delete-selected-variants">
                                            <i class="fas fa-trash me-2"></i>Xóa đã chọn (<span id="selected-count">0</span>)
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="40">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="select-all-variants">
                                                        <label class="form-check-label" for="select-all-variants">
                                                            <span class="visually-hidden">Chọn tất cả</span>
                                                        </label>
                                                    </div>
                                                </th>
                                                <th>Size</th>
                                                <th>Màu sắc</th>
                                                <th>Giá bán</th>
                                                <th>Giá sale</th>
                                                <th>Số lượng</th>
                                                <th>SKU</th>
                                                <th>Hoạt động</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody id="variants-table-body">
                                            <!-- Variants will be generated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>                    <div class="mb-3" id="stock-quantity-section" style="display: {{ old('has_variants') ? 'none' : 'block' }};">
                        <label for="stock_quantity" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" 
                               min="0" step="1" placeholder="Nhập số lượng tồn kho" required>
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-images me-2"></i>Hình ảnh sản phẩm
                    </h5>
                </div>
                <div class="card-body">                    <!-- Main Image -->
                    <div class="mb-4">
                        <label for="main_image" class="form-label">Ảnh chính <span class="text-danger">*</span></label>
                        <div class="image-upload-area main-image-upload" id="main-image-drop-area">
                            <div class="upload-content text-center p-4">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h6>Kéo thả ảnh vào đây hoặc</h6>
                                <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                       id="main_image" name="main_image" accept="image/*" required style="display: none;">
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('main_image').click()">
                                    <i class="fas fa-folder-open me-2"></i>Chọn ảnh
                                </button>
                                <div class="form-text mt-2">JPG, PNG, WebP tối đa 2MB</div>
                            </div>
                            @error('main_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- Preview -->
                            <div class="main-image-preview mt-3" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img id="main-image-preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-main-image" style="margin: -10px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Images -->
                    <div class="mb-3">
                        <label for="additional_images" class="form-label">Ảnh bổ sung</label>
                        <div class="image-upload-area additional-images-upload" id="additional-images-drop-area">
                            <div class="upload-content text-center p-4">
                                <i class="fas fa-images fa-2x text-muted mb-2"></i>
                                <h6>Kéo thả nhiều ảnh vào đây hoặc</h6>
                                <input type="file" class="form-control @error('additional_images') is-invalid @enderror" 
                                       id="additional_images" name="additional_images[]" accept="image/*" multiple style="display: none;">
                                <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('additional_images').click()">
                                    <i class="fas fa-folder-open me-2"></i>Chọn ảnh
                                </button>
                                <div class="form-text mt-2">Tối đa 5 ảnh, mỗi ảnh tối đa 2MB</div>
                            </div>
                            @error('additional_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('additional_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                          <!-- Additional Images Preview -->
                        <div class="additional-images-preview mt-3">
                            <div id="upload-summary" class="alert alert-info" style="display: none;">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="upload-count">0</span> ảnh đã chọn
                                <span id="upload-size" class="ms-2"></span>
                            </div>
                            <div class="row g-2" id="additional-images-container">
                                <!-- Additional image previews will be added here -->
                            </div>
                        </div>
                    </div>
                </div>            </div>

            <!-- SEO Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>Thông tin SEO
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                               maxlength="255">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Tiêu đề hiển thị trên kết quả tìm kiếm Google (tối đa 255 ký tự)</div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" 
                                  maxlength="500">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Mô tả hiển thị trên kết quả tìm kiếm Google (tối đa 500 ký tự)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status & Features -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Cài đặt
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" 
                               id="is_featured" name="is_featured" value="1" 
                               {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <strong>Sản phẩm nổi bật</strong>
                        </label>
                        <div class="form-text">Hiển thị trong danh sách sản phẩm nổi bật trên trang chủ</div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" 
                               id="is_active" name="is_active" value="1" 
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <strong>Kích hoạt sản phẩm</strong>
                        </label>
                        <div class="form-text">Sản phẩm sẽ hiển thị trên website</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Lưu sản phẩm
                        </button>
                        
                        <button type="button" class="btn btn-outline-secondary" 
                                onclick="if(confirm('Bạn có chắc muốn hủy? Dữ liệu chưa lưu sẽ bị mất.')) { window.location.href='{{ route('admin.products.index') }}'; }">
                            <i class="fas fa-times me-2"></i>Hủy bỏ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <ul class="mb-0">
                            <li>Tên sản phẩm nên ngắn gọn và dễ hiểu</li>
                            <li>SKU phải là duy nhất cho mỗi sản phẩm</li>
                            <li>Hình ảnh sẽ được tự động tạo placeholder</li>
                            <li>Giá gốc lớn hơn giá bán để hiển thị giảm giá</li>
                            <li>Sản phẩm nổi bật sẽ hiển thị ưu tiên trên trang chủ</li>
                        </ul>
                    </small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Price formatting functions (define globally)
function formatPrice(price) {
    if (!price || price === 0) return '';
    return parseInt(price).toLocaleString('vi-VN');
}

function unformatPrice(priceString) {
    if (!priceString) return '';
    return priceString.replace(/[^\d]/g, '');
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== ADMIN PRODUCT CREATE PAGE LOADED ===');
    console.log('Location:', window.location.href);
    
    // Auto generate SKU from product name
    const nameInput = document.getElementById('name');
    const skuInput = document.getElementById('sku');
      nameInput.addEventListener('input', function() {
        if (!skuInput.value) {
            const sku = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '')
                .substring(0, 20);
            skuInput.value = sku.toUpperCase();
        }
    });

    // Initialize price formatting for main price inputs
    function addPriceFormattingToInput(input) {
        if (!input) return;
        
        // Format on input
        input.addEventListener('input', function() {
            const cursorPosition = this.selectionStart;
            const oldLength = this.value.length;
            const unformatted = unformatPrice(this.value);
            const formatted = formatPrice(unformatted);
            
            this.value = formatted;
            
            // Restore cursor position
            const newLength = formatted.length;
            const newPosition = cursorPosition + (newLength - oldLength);
            this.setSelectionRange(newPosition, newPosition);
        });

        // Format on blur
        input.addEventListener('blur', function() {
            const unformatted = unformatPrice(this.value);
            this.value = formatPrice(unformatted);
        });
    }    // Apply price formatting to main price inputs
    addPriceFormattingToInput(document.getElementById('price'));
    addPriceFormattingToInput(document.getElementById('original_price'));

    // Handle stock quantity input to prevent leading zeros
    const stockInput = document.getElementById('stock_quantity');
    if (stockInput) {
        stockInput.addEventListener('input', function() {
            // Remove leading zeros
            let value = this.value;
            if (value.length > 1 && value.startsWith('0') && !value.startsWith('0.')) {
                this.value = value.replace(/^0+/, '') || '0';
            }
        });
        
        stockInput.addEventListener('blur', function() {
            // Clean up the value on blur
            let value = parseInt(this.value);
            if (isNaN(value) || value < 0) {
                this.value = '';
            } else {
                this.value = value;
            }
        });
        
        // Clear default value on first focus
        let firstFocus = true;
        stockInput.addEventListener('focus', function() {
            if (firstFocus && this.value === '0') {
                this.value = '';
                firstFocus = false;
            }
        });
    }

    // Auto generate meta title from product name
    const metaTitleInput = document.getElementById('meta_title');
    nameInput.addEventListener('input', function() {
        if (!metaTitleInput.value) {
            metaTitleInput.value = this.value + ' - Hang The Thao';
        }
    });

    // Process sizes and colors before form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        // Process sizes
        const sizesInput = document.getElementById('sizes');
        const sizesValue = sizesInput ? sizesInput.value : '';
        if (sizesValue) {
            const sizesArray = sizesValue.split(',').map(s => s.trim()).filter(s => s);
            // Create hidden input for sizes array
            sizesArray.forEach((size, index) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `sizes[${index}]`;
                hiddenInput.value = size;
                this.appendChild(hiddenInput);
            });
        }

        // Process colors
        const colorsInput = document.getElementById('colors');
        const colorsValue = colorsInput ? colorsInput.value : '';
        if (colorsValue) {
            const colorsArray = colorsValue.split(',').map(c => c.trim()).filter(c => c);
            // Create hidden input for colors array
            colorsArray.forEach((color, index) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `colors[${index}]`;
                hiddenInput.value = color;
                this.appendChild(hiddenInput);
            });
        }
    });    // Price validation
    const priceInput = document.getElementById('price');
    const originalPriceInput = document.getElementById('original_price');
    
    originalPriceInput.addEventListener('change', function() {
        const price = parseFloat(priceInput.value) || 0;
        const originalPrice = parseFloat(this.value) || 0;
        
        if (originalPrice > 0 && originalPrice <= price) {
            alert('Giá gốc phải lớn hơn giá bán để hiển thị giảm giá!');
            this.focus();
        }
    });    // Image upload and preview functionality
    const mainImageInput = document.getElementById('main_image');
    const mainImagePreview = document.querySelector('.main-image-preview');
    const mainImagePreviewImg = document.getElementById('main-image-preview');
    const removeMainImageBtn = document.querySelector('.remove-main-image');
    const mainDropArea = document.getElementById('main-image-drop-area');
    
    // Drag and drop for main image
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        mainDropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        mainDropArea.addEventListener(eventName, () => mainDropArea.classList.add('dragover'), false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        mainDropArea.addEventListener(eventName, () => mainDropArea.classList.remove('dragover'), false);
    });
    
    mainDropArea.addEventListener('drop', handleMainImageDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function handleMainImageDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                // Create a new FileList with the dropped file
                const fileList = new DataTransfer();
                fileList.items.add(file);
                mainImageInput.files = fileList.files;
                
                // Trigger the change event
                mainImageInput.dispatchEvent(new Event('change'));
            } else {
                alert('Vui lòng chỉ thả file ảnh!');
            }
        }
    }
    
    // Main image preview
    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn file ảnh!');
                this.value = '';
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Kích thước ảnh không được vượt quá 2MB!');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                mainImagePreviewImg.src = e.target.result;
                mainImagePreview.style.display = 'block';
                // Hide upload area content
                mainDropArea.querySelector('.upload-content').style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            mainImagePreview.style.display = 'none';
            mainDropArea.querySelector('.upload-content').style.display = 'block';
        }
    });
    
    // Remove main image
    if (removeMainImageBtn) {
        removeMainImageBtn.addEventListener('click', function() {
            mainImageInput.value = '';
            mainImagePreview.style.display = 'none';
            mainDropArea.querySelector('.upload-content').style.display = 'block';
        });
    }
    
    // Additional images functionality
    const additionalImagesInput = document.getElementById('additional_images');
    const additionalImagesContainer = document.getElementById('additional-images-container');
    const additionalDropArea = document.getElementById('additional-images-drop-area');
    
    // Drag and drop for additional images
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        additionalDropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        additionalDropArea.addEventListener(eventName, () => additionalDropArea.classList.add('dragover'), false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        additionalDropArea.addEventListener(eventName, () => additionalDropArea.classList.remove('dragover'), false);
    });
    
    additionalDropArea.addEventListener('drop', handleAdditionalImagesDrop, false);
    
    function handleAdditionalImagesDrop(e) {
        const dt = e.dataTransfer;
        const files = Array.from(dt.files).filter(file => file.type.startsWith('image/'));
        
        if (files.length > 0) {
            const fileList = new DataTransfer();
            files.forEach(file => fileList.items.add(file));
            additionalImagesInput.files = fileList.files;
            
            // Trigger the change event
            additionalImagesInput.dispatchEvent(new Event('change'));
        }
    }
      // Additional images preview
    additionalImagesInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        // Clear existing previews
        additionalImagesContainer.innerHTML = '';
        
        // Validate number of files
        if (files.length > 5) {
            alert('Chỉ được chọn tối đa 5 ảnh bổ sung!');
            this.value = '';
            updateUploadSummary(0, 0);
            return;
        }
        
        let totalSize = 0;
        let validFiles = 0;
        
        files.forEach((file, index) => {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert(`File ${file.name} không phải là ảnh!`);
                return;
            }
            
            // Validate file size
            if (file.size > 2 * 1024 * 1024) {
                alert(`Ảnh ${file.name} vượt quá 2MB!`);
                return;
            }
            
            totalSize += file.size;
            validFiles++;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const previewHtml = `
                    <div class="col-auto additional-image-item">
                        <div class="position-relative">
                            <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                 class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-additional-image" 
                                    style="margin: -5px;" data-index="${index}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="file-info">
                            <div class="fw-bold" style="font-size: 11px;">${file.name}</div>
                            <div class="text-muted">${fileSize} MB</div>
                        </div>
                    </div>
                `;
                additionalImagesContainer.insertAdjacentHTML('beforeend', previewHtml);
            };
            reader.readAsDataURL(file);
        });
        
        updateUploadSummary(validFiles, totalSize);
        
        if (files.length > 0) {
            additionalDropArea.querySelector('.upload-content').style.display = 'none';
        }
    });

    function updateUploadSummary(count, totalSize) {
        const summary = document.getElementById('upload-summary');
        const countSpan = document.getElementById('upload-count');
        const sizeSpan = document.getElementById('upload-size');
        
        if (count > 0) {
            countSpan.textContent = count;
            sizeSpan.textContent = `(${(totalSize / 1024 / 1024).toFixed(2)} MB)`;
            summary.style.display = 'block';
        } else {
            summary.style.display = 'none';
        }
    }
      // Remove additional images
    additionalImagesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-additional-image')) {
            const button = e.target.closest('.remove-additional-image');
            const imageItem = button.closest('.additional-image-item');
            const index = parseInt(button.dataset.index);
            
            // Remove preview
            imageItem.remove();
            
            // Update summary
            const remainingItems = additionalImagesContainer.querySelectorAll('.additional-image-item');
            updateUploadSummary(remainingItems.length, 0); // Approximate, as we don't track individual sizes after preview
            
            // Update file input (remove specific file is complex, so we clear all)
            // User will need to reselect if they want to remove specific images
            if (remainingItems.length === 0) {
                additionalImagesInput.value = '';
                additionalDropArea.querySelector('.upload-content').style.display = 'block';
                updateUploadSummary(0, 0);
            }        }
    });    // Variants handling
    console.log('=== INITIALIZING VARIANTS HANDLING ===');
    const hasVariantsCheckbox = document.getElementById('has_variants');
    const simpleProductSection = document.getElementById('simple-product-section');
    const variantsSection = document.getElementById('variants-section');
    const stockQuantitySection = document.getElementById('stock-quantity-section');
    const generateVariantsBtn = document.getElementById('generate-variants-btn');
    const variantsTableContainer = document.getElementById('variants-table-container');
    const variantsTableBody = document.getElementById('variants-table-body');

    console.log('Variants elements found:', {
        hasVariantsCheckbox: !!hasVariantsCheckbox,
        generateVariantsBtn: !!generateVariantsBtn,
        variantsTableBody: !!variantsTableBody,
        variantsTableContainer: !!variantsTableContainer
    });

    if (!generateVariantsBtn) {
        console.error('CRITICAL: generate-variants-btn element not found!');
        console.log('Available buttons:', Array.from(document.querySelectorAll('button')).map(b => ({ id: b.id, text: b.textContent.trim() })));
    }    // Toggle product type
    if (hasVariantsCheckbox) {
        console.log('Adding change event to hasVariantsCheckbox');
        hasVariantsCheckbox.addEventListener('change', function() {
            console.log('hasVariantsCheckbox changed to:', this.checked);
            if (this.checked) {
                simpleProductSection.style.display = 'none';
                variantsSection.style.display = 'block';
                stockQuantitySection.style.display = 'none';
                
                // Make stock_quantity not required for variants
                document.getElementById('stock_quantity').removeAttribute('required');
            } else {
                simpleProductSection.style.display = 'block';
                variantsSection.style.display = 'none';
                stockQuantitySection.style.display = 'block';
                variantsTableContainer.style.display = 'none';
                
                // Make stock_quantity required for simple products
                document.getElementById('stock_quantity').setAttribute('required', 'required');
                
                // Clear variants table
                variantsTableBody.innerHTML = '';
            }
        });
    } else {
        console.error('hasVariantsCheckbox not found!');
    }// Generate variants
    if (generateVariantsBtn) {
        generateVariantsBtn.addEventListener('click', function() {
            console.log('Generate variants clicked');
            
            const selectedSizes = Array.from(document.querySelectorAll('.variant-size:checked')).map(cb => cb.value);
            const selectedColors = Array.from(document.querySelectorAll('.variant-color:checked')).map(cb => ({
                name: cb.value,
                code: cb.dataset.colorCode
            }));

            console.log('Selected sizes:', selectedSizes);
            console.log('Selected colors:', selectedColors);

            if (selectedSizes.length === 0 && selectedColors.length === 0) {
                alert('Vui lòng chọn ít nhất một size hoặc màu sắc!');
                return;
            }

        // Generate combinations
        const variants = [];
        
        if (selectedSizes.length > 0 && selectedColors.length > 0) {
            // Both size and color
            selectedSizes.forEach(size => {
                selectedColors.forEach(color => {
                    variants.push({
                        size: size,
                        color: color.name,
                        colorCode: color.code
                    });
                });
            });
        } else if (selectedSizes.length > 0) {
            // Only sizes
            selectedSizes.forEach(size => {
                variants.push({
                    size: size,
                    color: '',
                    colorCode: ''
                });
            });
        } else {
            // Only colors
            selectedColors.forEach(color => {
                variants.push({
                    size: '',
                    color: color.name,
                    colorCode: color.code
                });
            });
        }        // Generate table rows
        generateVariantsTable(variants);
        variantsTableContainer.style.display = 'block';
    });
    } else {
        console.error('generateVariantsBtn not found!');
    }    function generateVariantsTable(variants) {
        console.log('generateVariantsTable called with:', variants);
        
        if (!variants || variants.length === 0) {
            console.error('No variants provided');
            return;
        }
        
        if (!variantsTableBody) {
            console.error('variantsTableBody not found');
            return;
        }
        
        const priceInput = document.getElementById('price');
        const skuInput = document.getElementById('sku');
        const basePrice = priceInput ? unformatPrice(priceInput.value) || 0 : 0;
        const baseSku = skuInput ? skuInput.value || '' : '';
        
        console.log('Base price (unformatted):', basePrice);
        console.log('Base SKU:', baseSku);
        
        variantsTableBody.innerHTML = '';
        
        variants.forEach((variant, index) => {
            try {
                const row = document.createElement('tr');
                const variantSku = baseSku + (variant.size ? `-${variant.size}` : '') + (variant.color ? `-${variant.color.replace(/\s+/g, '')}` : '');
                
                console.log(`Creating row ${index}:`, variant, 'SKU:', variantSku);
                
                row.innerHTML = `
                    <td>
                        <div class="form-check">
                            <input class="form-check-input variant-checkbox" type="checkbox">
                        </div>
                    </td>
                    <td>
                        <input type="hidden" name="variants[${index}][size]" value="${variant.size || ''}">
                        ${variant.size || '-'}
                    </td>
                    <td>
                        <input type="hidden" name="variants[${index}][color]" value="${variant.color || ''}">
                        <input type="hidden" name="variants[${index}][color_code]" value="${variant.colorCode || ''}">
                        <div class="d-flex align-items-center">
                            ${variant.colorCode ? `<span class="color-preview me-2" style="width: 20px; height: 20px; border-radius: 50%; background-color: ${variant.colorCode}; border: 1px solid #ddd;"></span>` : ''}
                            ${variant.color || '-'}
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control price-input" name="variants[${index}][price]" 
                                   value="${formatPrice(basePrice)}" required>
                            <span class="input-group-text">₫</span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control price-input" name="variants[${index}][sale_price]" 
                                   placeholder="Tùy chọn">
                            <span class="input-group-text">₫</span>
                        </div>
                    </td>                    <td>
                        <input type="number" class="form-control form-control-sm stock-input" name="variants[${index}][stock_quantity]" 
                               placeholder="0" min="0" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${index}][sku]" 
                               value="${variantSku}" required>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="variants[${index}][is_active]" 
                                   value="1" checked>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                variantsTableBody.appendChild(row);
            } catch (error) {
                console.error(`Error creating row ${index}:`, error, variant);
            }
        });
        // Initialize bulk actions after table is generated
        initializeBulkActionsCreate();
        addRemoveVariantListeners();
        addPriceFormatting(); // Add price formatting to new variant inputs
    }

    function initializeBulkActionsCreate() {
        const selectAllCheckbox = document.getElementById('select-all-variants');
        const deleteSelectedBtn = document.getElementById('delete-selected-variants');
        
        // Remove existing event listeners and add new ones
        if (selectAllCheckbox) {
            selectAllCheckbox.replaceWith(selectAllCheckbox.cloneNode(true));
            const newSelectAllCheckbox = document.getElementById('select-all-variants');
            
            newSelectAllCheckbox.addEventListener('change', function() {
                const variantCheckboxes = document.querySelectorAll('.variant-checkbox');
                variantCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionsCreate();
            });
        }

        if (deleteSelectedBtn) {
            deleteSelectedBtn.replaceWith(deleteSelectedBtn.cloneNode(true));
            const newDeleteSelectedBtn = document.getElementById('delete-selected-variants');
            
            newDeleteSelectedBtn.addEventListener('click', function() {
                const checkedCheckboxes = document.querySelectorAll('.variant-checkbox:checked');
                
                if (checkedCheckboxes.length === 0) {
                    alert('Vui lòng chọn ít nhất một biến thể để xóa.');
                    return;
                }

                if (confirm(`Bạn có chắc chắn muốn xóa ${checkedCheckboxes.length} biến thể đã chọn?`)) {
                    checkedCheckboxes.forEach(checkbox => {
                        const row = checkbox.closest('tr');
                        if (row) {
                            row.remove();
                        }
                    });
                    
                    reindexVariantsCreate();
                    updateBulkActionsCreate();
                    
                    // Reset select all checkbox
                    const selectAll = document.getElementById('select-all-variants');
                    if (selectAll) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                    }
                }
            });
        }
        
        // Individual checkbox change handler
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('variant-checkbox')) {
                updateBulkActionsCreate();
                
                // Update select all checkbox state
                const allCheckboxes = document.querySelectorAll('.variant-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.variant-checkbox:checked');
                const selectAll = document.getElementById('select-all-variants');
                
                if (selectAll) {
                    if (checkedCheckboxes.length === 0) {
                        selectAll.indeterminate = false;
                        selectAll.checked = false;
                    } else if (checkedCheckboxes.length === allCheckboxes.length) {
                        selectAll.indeterminate = false;
                        selectAll.checked = true;
                    } else {
                        selectAll.indeterminate = true;
                        selectAll.checked = false;
                    }
                }
            }
        });
    }

    function updateBulkActionsCreate() {
        const checkedCheckboxes = document.querySelectorAll('.variant-checkbox:checked');
        const count = checkedCheckboxes.length;
        const bulkActions = document.querySelector('.bulk-actions');
        const selectedCountSpan = document.getElementById('selected-count');
        
        if (bulkActions) {
            bulkActions.style.display = count > 0 ? 'block' : 'none';
        }
        
        if (selectedCountSpan) {
            selectedCountSpan.textContent = count;
        }
    }    function addRemoveVariantListeners() {
        document.querySelectorAll('.remove-variant').forEach(button => {
            // Skip if already has event listener
            if (button.dataset.listenerAdded) return;
            button.dataset.listenerAdded = 'true';
            
            button.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa biến thể này?')) {
                    this.closest('tr').remove();
                    reindexVariantsCreate();
                    updateBulkActionsCreate();
                }
            });
        });
    }

    function reindexVariantsCreate() {
        const rows = document.querySelectorAll('#variants-table-body tr');
        rows.forEach((row, index) => {            row.querySelectorAll('input, select').forEach(input => {
                if (input.name && input.name.includes('[')) {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                }
            });
        });
    }    // Add price formatting to variant price inputs
    function addPriceFormatting() {
        // Handle existing price inputs (variant table only)
        document.querySelectorAll('.price-input').forEach(input => {
            // Skip main price inputs as they're already handled
            if (input.id === 'price' || input.id === 'original_price') return;
            
            // Skip if already has event listeners
            if (input.dataset.priceFormatted) return;
            input.dataset.priceFormatted = 'true';
            
            // Format on input
            input.addEventListener('input', function() {
                const cursorPosition = this.selectionStart;
                const oldLength = this.value.length;
                const unformatted = unformatPrice(this.value);
                const formatted = formatPrice(unformatted);
                
                this.value = formatted;
                
                // Restore cursor position
                const newLength = formatted.length;
                const newPosition = cursorPosition + (newLength - oldLength);
                this.setSelectionRange(newPosition, newPosition);
            });

            // Format on blur
            input.addEventListener('blur', function() {
                const unformatted = unformatPrice(this.value);
                this.value = formatPrice(unformatted);
            });
        });
    }    // Initialize price formatting
    addPriceFormatting();
    
    // Handle stock quantity inputs to prevent leading zeros
    function handleStockInputs() {
        // Handle main stock input
        const mainStockInput = document.getElementById('stock_quantity');
        if (mainStockInput) {
            handleSingleStockInput(mainStockInput);
        }
        
        // Handle variant stock inputs
        document.querySelectorAll('.stock-input, input[name*="[stock_quantity]"]').forEach(input => {
            handleSingleStockInput(input);
        });
    }
    
    function handleSingleStockInput(input) {
        // Skip if already processed
        if (input.dataset.stockProcessed) return;
        input.dataset.stockProcessed = 'true';
        
        input.addEventListener('input', function() {
            // Remove leading zeros
            let value = this.value;
            if (value.length > 1 && value.startsWith('0') && !value.includes('.')) {
                this.value = value.replace(/^0+/, '') || '';
            }
        });
        
        input.addEventListener('blur', function() {
            // Clean up the value on blur
            let value = parseInt(this.value);
            if (isNaN(value) || value < 0) {
                this.value = '';
            } else {
                this.value = value;
            }
        });
        
        // Clear placeholder-like default values on first focus
        let firstFocus = true;
        input.addEventListener('focus', function() {
            if (firstFocus && (this.value === '0' || this.value === this.placeholder)) {
                this.value = '';
                firstFocus = false;
            }
        });
    }
    
    // Initialize stock input handlers
    handleStockInputs();
    
    // Re-apply stock handlers when new variants are created
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                handleStockInputs();
            }
        });
    });
    
    const variantsContainer = document.getElementById('variants-table-body');
    if (variantsContainer) {
        observer.observe(variantsContainer, { childList: true, subtree: true });
    }

    // Test function - accessible from browser console
    window.testVariants = function() {
        console.log('=== TESTING VARIANTS FUNCTION ===');
        
        // Enable variants mode
        const hasVariantsCheckbox = document.getElementById('has_variants');
        if (hasVariantsCheckbox) {
            hasVariantsCheckbox.checked = true;
            hasVariantsCheckbox.dispatchEvent(new Event('change'));
        }
        
        // Select some test sizes
        const sizeS = document.getElementById('size_S');
        const sizeM = document.getElementById('size_M');
        if (sizeS) sizeS.checked = true;
        if (sizeM) sizeM.checked = true;
        
        // Select some test colors
        const colorRed = document.querySelector('.variant-color[value="Đỏ"]');
        const colorBlue = document.querySelector('.variant-color[value="Xanh"]');
        if (colorRed) colorRed.checked = true;
        if (colorBlue) colorBlue.checked = true;
        
        // Set test price
        const priceInput = document.getElementById('price');
        if (priceInput) priceInput.value = '500000';
        
        // Click generate button
        const generateBtn = document.getElementById('generate-variants-btn');
        if (generateBtn) {
            console.log('Clicking generate button...');
            generateBtn.click();
        } else {
            console.error('Generate button not found!');
        }
    };    console.log('=== TEST FUNCTION READY ===');
    console.log('Run "testVariants()" in console to test variants generation');
});

// Load simple fix
const fixScript = document.createElement('script');
fixScript.src = '/variants-fix.js';
document.head.appendChild(fixScript);

// Load debug script
const debugScript = document.createElement('script');
debugScript.src = '/js/debug-form.js';
document.head.appendChild(debugScript);// Form submission handler to convert formatted prices back to numbers
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('=== FORM SUBMISSION ===');
                
                // Convert all formatted prices back to numbers before submission
                document.querySelectorAll('.price-input').forEach(input => {
                    if (input.value) {
                        const originalValue = input.value;
                        input.value = unformatPrice(input.value);
                        console.log(`Price converted: ${originalValue} → ${input.value}`);
                    }
                });
                
                // Check if has_variants is checked
                const hasVariantsCheckbox = document.getElementById('has_variants');
                const hasVariants = hasVariantsCheckbox ? hasVariantsCheckbox.checked : false;
                console.log('Has variants:', hasVariants);
                
                if (hasVariants) {
                    const variantRows = document.querySelectorAll('#variants-table-body tr');
                    console.log('Number of variant rows:', variantRows.length);
                    
                    if (variantRows.length === 0) {
                        e.preventDefault();
                        alert('Vui lòng tạo ít nhất một biến thể hoặc tắt chế độ variants.');
                        
                        // Re-format prices since we prevented submission
                        document.querySelectorAll('.price-input').forEach(input => {
                            if (input.value) {
                                input.value = formatPrice(input.value);
                            }
                        });
                        
                        return false;
                    }
                }
                
                console.log('Form submission allowed');
            });
        }
    });
</script>
@endpush
