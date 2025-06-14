@extends('admin.layout')

@section('title', 'Chỉnh sửa sản phẩm: ' . $product->name)

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

.current-images img {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.remove-current-image {
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
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm: {{ $product->name }}
    </h1>
    <div>
        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info me-2">
            <i class="fas fa-eye me-2"></i>Xem chi tiết
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
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
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="sku" class="form-label">Mã SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mã định danh duy nhất cho sản phẩm</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" 
                                       min="0" step="1000" required>
                                <span class="input-group-text">₫</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="original_price" class="form-label">Giá gốc (để tính giảm giá)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('original_price') is-invalid @enderror" 
                                       id="original_price" name="original_price" value="{{ old('original_price', $product->original_price) }}" 
                                       min="0" step="1000">
                                <span class="input-group-text">₫</span>
                            </div>
                            @error('original_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>                    <div class="row">                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" 
                                        {{ old('category', $product->category) == $category->slug ? 'selected' : '' }}>
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
                                   id="brand" name="brand" value="{{ old('brand', $product->brand) }}" 
                                   placeholder="Nike, Adidas, Puma...">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                               min="0" required>
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
                <div class="card-body">
                    <!-- Current Images -->
                    @if($product->images && count($product->images) > 0)
                    <div class="mb-4">
                        <label class="form-label">Ảnh hiện tại</label>
                        <div class="current-images">
                            <div class="row g-3">
                                @foreach($product->images as $index => $image)
                                <div class="col-auto current-image-item" data-index="{{ $index }}">
                                    <div class="position-relative">
                                        <img src="{{ $image }}" alt="Product Image {{ $index + 1 }}" 
                                             class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-current-image" 
                                                style="margin: -5px;" data-index="{{ $index }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @if($index === 0)
                                        <span class="badge bg-primary position-absolute bottom-0 start-0 m-1">Ảnh chính</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="keep_images" id="keep_images" value="{{ implode(',', array_keys($product->images)) }}">
                    </div>
                    @endif
                    
                    <!-- New Main Image -->
                    <div class="mb-4">
                        <label for="main_image" class="form-label">Ảnh chính mới (tùy chọn)</label>
                        <div class="main-image-upload">
                            <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                   id="main_image" name="main_image" accept="image/*">
                            @error('main_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Chọn ảnh chính mới để thay thế (JPG, PNG, WebP tối đa 2MB)</div>
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
                        <label for="additional_images" class="form-label">Ảnh bổ sung mới (tùy chọn)</label>
                        <input type="file" class="form-control @error('additional_images') is-invalid @enderror" 
                               id="additional_images" name="additional_images[]" accept="image/*" multiple>
                        @error('additional_images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('additional_images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Chọn nhiều ảnh bổ sung mới để thêm vào sản phẩm (tối đa 5 ảnh, mỗi ảnh tối đa 2MB)</div>
                        
                        <!-- Additional Images Preview -->
                        <div class="additional-images-preview mt-3">
                            <div class="row g-2" id="additional-images-container">
                                <!-- Additional image previews will be added here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Variants -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-palette me-2"></i>Biến thể sản phẩm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sizes" class="form-label">Kích thước</label>
                            <div class="form-text mb-2">Nhập các size cách nhau bởi dấu phẩy (VD: S, M, L, XL)</div>
                            <input type="text" class="form-control @error('sizes') is-invalid @enderror" 
                                   id="sizes" name="sizes_input" value="{{ old('sizes_input', is_array($product->sizes) ? implode(', ', $product->sizes) : '') }}" 
                                   placeholder="S, M, L, XL">
                            @error('sizes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="colors" class="form-label">Màu sắc</label>
                            <div class="form-text mb-2">Nhập các màu cách nhau bởi dấu phẩy (VD: Đỏ, Xanh, Trắng)</div>
                            <input type="text" class="form-control @error('colors') is-invalid @enderror" 
                                   id="colors" name="colors_input" value="{{ old('colors_input', is_array($product->colors) ? implode(', ', $product->colors) : '') }}"
                                   placeholder="Đỏ, Xanh, Trắng">
                            @error('colors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Publishing Options -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Tùy chọn xuất bản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Đang bán</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Ẩn sản phẩm</option>
                            <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" 
                               id="is_featured" name="is_featured" 
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <i class="fas fa-star text-warning me-1"></i>Sản phẩm nổi bật
                        </label>
                        <div class="form-text">Hiển thị trong danh sách sản phẩm nổi bật</div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" 
                               id="is_active" name="is_active" 
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-eye text-success me-1"></i>Hiển thị công khai
                        </label>
                        <div class="form-text">Cho phép khách hàng xem sản phẩm</div>
                    </div>
                </div>
            </div>

            <!-- SEO Options -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>Tối ưu SEO
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Tiêu đề SEO</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" 
                               maxlength="255" placeholder="Tiêu đề hiển thị trên Google">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Tối đa 255 ký tự</div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Mô tả SEO</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" 
                                  maxlength="500" placeholder="Mô tả ngắn hiển thị trên Google">{{ old('meta_description', $product->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Tối đa 500 ký tự</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Hành động
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật sản phẩm
                        </button>
                        
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
                            <i class="fas fa-eye me-2"></i>Xem trước
                        </a>
                        
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Xóa sản phẩm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm <strong>"{{ $product->name }}"</strong> không?</p>
                <p class="text-danger">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate SKU from name
    const nameInput = document.getElementById('name');
    const skuInput = document.getElementById('sku');
    
    nameInput.addEventListener('blur', function() {
        if (!skuInput.value) {
            const sku = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '-')
                .slice(0, 20) + '-' + Date.now().toString().slice(-6);
            skuInput.value = sku;
        }
    });

    // Auto-generate meta title from name
    const metaTitleInput = document.getElementById('meta_title');
    nameInput.addEventListener('blur', function() {
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
    });

    // Price validation
    const priceInput = document.getElementById('price');
    const originalPriceInput = document.getElementById('original_price');
    
    originalPriceInput.addEventListener('change', function() {
        const price = parseFloat(priceInput.value) || 0;
        const originalPrice = parseFloat(this.value) || 0;
        
        if (originalPrice > 0 && originalPrice <= price) {
            alert('Giá gốc phải lớn hơn giá bán để hiển thị giảm giá!');
            this.focus();
        }
    });

    // Image upload and preview functionality
    const mainImageInput = document.getElementById('main_image');
    const mainImagePreview = document.querySelector('.main-image-preview');
    const mainImagePreviewImg = document.getElementById('main-image-preview');
    const removeMainImageBtn = document.querySelector('.remove-main-image');
    
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
            };
            reader.readAsDataURL(file);
        } else {
            mainImagePreview.style.display = 'none';
        }
    });
    
    // Remove main image
    if (removeMainImageBtn) {
        removeMainImageBtn.addEventListener('click', function() {
            mainImageInput.value = '';
            mainImagePreview.style.display = 'none';
        });
    }
    
    // Additional images preview
    const additionalImagesInput = document.getElementById('additional_images');
    const additionalImagesContainer = document.getElementById('additional-images-container');
    
    additionalImagesInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        // Clear existing previews
        additionalImagesContainer.innerHTML = '';
        
        // Validate number of files
        if (files.length > 5) {
            alert('Chỉ được chọn tối đa 5 ảnh bổ sung!');
            this.value = '';
            return;
        }
        
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
            
            const reader = new FileReader();
            reader.onload = function(e) {
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
                        <small class="text-muted d-block text-center mt-1">${file.name}</small>
                    </div>
                `;
                additionalImagesContainer.insertAdjacentHTML('beforeend', previewHtml);
            };
            reader.readAsDataURL(file);
        });
    });
    
    // Remove additional images
    additionalImagesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-additional-image')) {
            const button = e.target.closest('.remove-additional-image');
            const imageItem = button.closest('.additional-image-item');
            const index = parseInt(button.dataset.index);
            
            // Remove preview
            imageItem.remove();
            
            // Update file input (remove specific file is complex, so we clear all)
            // User will need to reselect if they want to remove specific images
            const remainingItems = additionalImagesContainer.querySelectorAll('.additional-image-item');
            if (remainingItems.length === 0) {
                additionalImagesInput.value = '';
            }
        }
    });

    // Handle removing current images
    const keepImagesInput = document.getElementById('keep_images');
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-current-image')) {
            const button = e.target.closest('.remove-current-image');
            const imageItem = button.closest('.current-image-item');
            const index = parseInt(button.dataset.index);
            
            // Remove from DOM
            imageItem.remove();
            
            // Update keep_images input
            let keepImages = keepImagesInput.value.split(',').filter(i => i !== index.toString());
            keepImagesInput.value = keepImages.join(',');
        }
    });
});
</script>
@endpush
