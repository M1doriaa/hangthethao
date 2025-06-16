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

                    <div class="row">                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control price-input @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ $product->price ? number_format($product->price, 0, ',', ',') : '' }}" 
                                       placeholder="0" required data-original-value="{{ $product->price }}">
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
                                       id="original_price" name="original_price" value="{{ $product->original_price ? number_format($product->original_price, 0, ',', ',') : '' }}" 
                                       placeholder="0" data-original-value="{{ $product->original_price }}">
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
            </div>            <!-- Product Type & Variants -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Loại sản phẩm & Biến thể
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Product Type Section -->
                    <div class="mb-4">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants" value="1" {{ old('has_variants', $product->has_variants) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_variants">
                                    <strong>Sản phẩm có nhiều biến thể (size, màu sắc, giá khác nhau)</strong>
                                </label>
                            </div>
                            <div class="form-text">Bật tùy chọn này nếu sản phẩm có nhiều size/màu với giá khác nhau</div>
                        </div>

                        <!-- Simple Product Section -->
                        <div id="simple-product-section" style="display: {{ old('has_variants', $product->has_variants) ? 'none' : 'block' }};">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Sản phẩm đơn giản:</strong> Sử dụng giá cố định ở trên và có thể thêm size/màu sắc tùy chọn.
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sizes_simple" class="form-label">Kích thước</label>
                                    <div class="form-text mb-2">Nhập các size cách nhau bởi dấu phẩy (VD: S, M, L, XL)</div>
                                    <input type="text" class="form-control" 
                                           id="sizes_simple" name="sizes_input" value="{{ old('sizes_input', is_array($product->sizes) ? implode(', ', $product->sizes) : '') }}" 
                                           placeholder="S, M, L, XL">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="colors_simple" class="form-label">Màu sắc</label>
                                    <div class="form-text mb-2">Nhập các màu cách nhau bởi dấu phẩy (VD: Đỏ, Xanh, Trắng)</div>
                                    <input type="text" class="form-control" 
                                           id="colors_simple" name="colors_input" value="{{ old('colors_input', is_array($product->colors) ? implode(', ', $product->colors) : '') }}"
                                           placeholder="Đỏ, Xanh, Trắng">
                                </div>
                            </div>
                        </div>

                        <!-- Variants Section -->
                        <div id="variants-section" style="display: {{ old('has_variants', $product->has_variants) ? 'block' : 'none' }};">
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
                                            @foreach([
                                                ['name' => 'Đỏ', 'code' => '#ff0000'],
                                                ['name' => 'Xanh lá', 'code' => '#00ff00'],
                                                ['name' => 'Xanh dương', 'code' => '#0000ff'],
                                                ['name' => 'Trắng', 'code' => '#ffffff'],
                                                ['name' => 'Đen', 'code' => '#000000'],
                                                ['name' => 'Vàng', 'code' => '#ffff00']
                                            ] as $color)
                                            <div class="col-md-6 col-12 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input variant-color" type="checkbox" 
                                                           id="color_{{ Str::slug($color['name']) }}" value="{{ $color['name'] }}" data-color-code="{{ $color['code'] }}">
                                                    <label class="form-check-label d-flex align-items-center" for="color_{{ Str::slug($color['name']) }}">
                                                        <span class="color-swatch me-2" style="width: 16px; height: 16px; background-color: {{ $color['code'] }}; border: 1px solid #ddd; border-radius: 2px;"></span>
                                                        {{ $color['name'] }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                              <div class="text-end mb-3">
                                <button type="button" class="btn btn-outline-primary" id="generate-variants">
                                    <i class="fas fa-magic me-2"></i>Tạo biến thể
                                </button>
                            </div>
                            
                            <!-- Variants Table -->
                            <div id="variants-table-container">
                                @if($product->has_variants && $product->variants->count() > 0)
                                <!-- Bulk Actions -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="bulk-actions" style="display: none;">
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="delete-selected-variants">
                                            <i class="fas fa-trash me-2"></i>Xóa đã chọn (<span id="selected-count">0</span>)
                                        </button>
                                    </div>
                                    <div class="text-muted">
                                        <small>Tổng {{ $product->variants->count() }} biến thể</small>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="variants-table">
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
                                                <th>Giá bán (₫)</th>
                                                <th>Giá sale (₫)</th>
                                                <th>Tồn kho</th>
                                                <th>SKU</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variants as $index => $variant)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input variant-checkbox" type="checkbox" value="{{ $variant->id }}">
                                                    </div>
                                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variants[{{ $index }}][size]" value="{{ $variant->size }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variants[{{ $index }}][color]" value="{{ $variant->color }}" required>
                                                    <input type="hidden" name="variants[{{ $index }}][color_code]" value="{{ $variant->color_code }}">
                                                </td>                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control price-input" name="variants[{{ $index }}][price]" value="{{ number_format($variant->price, 0, ',', ',') }}" required>
                                                        <span class="input-group-text">₫</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control price-input" name="variants[{{ $index }}][sale_price]" value="{{ $variant->sale_price ? number_format($variant->sale_price, 0, ',', ',') : '' }}">
                                                        <span class="input-group-text">₫</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" name="variants[{{ $index }}][stock_quantity]" value="{{ $variant->stock_quantity }}" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variants[{{ $index }}][sku]" value="{{ $variant->sku }}">
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="variants[{{ $index }}][is_active]" value="1" {{ $variant->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
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
// Edit Product Variants JavaScript - Inline version
console.log('Loading edit product variants inline script...');

// Price formatting functions
function formatPrice(price) {
    if (!price || price === 0) return '';
    return parseInt(price).toLocaleString('vi-VN');
}

function unformatPrice(priceString) {
    if (!priceString) return '';
    return priceString.replace(/[^\d]/g, '');
}

// Debug functions
window.debugEditForm = function() {
    console.log('=== DEBUGGING EDIT FORM ===');
    
    const elements = {
        hasVariantsCheckbox: document.getElementById('has_variants'),
        generateVariantsBtn: document.getElementById('generate-variants'),
        variantsTableContainer: document.getElementById('variants-table-container'),
        variantsSection: document.getElementById('variants-section'),
        simpleProductSection: document.getElementById('simple-product-section')
    };
    
    console.log('Elements found:');
    Object.entries(elements).forEach(([key, element]) => {
        console.log('  ' + key + ':', !!element, element ? element.tagName : 'NOT FOUND');
    });
    
    // Check checkboxes
    const sizeCheckboxes = document.querySelectorAll('.variant-size');
    const colorCheckboxes = document.querySelectorAll('.variant-color');
    console.log('Variant options:');
    console.log('  Sizes: ' + sizeCheckboxes.length);
    console.log('  Colors: ' + colorCheckboxes.length);
    
    // Check existing variants
    const existingVariants = document.querySelectorAll('#variants-table tbody tr');
    console.log('Existing variants: ' + existingVariants.length);
    
    return elements;
};

window.testEditVariantGeneration = function() {
    console.log('=== TESTING VARIANT GENERATION IN EDIT ===');
    
    // Check if variants section is visible
    const hasVariants = document.getElementById('has_variants');
    if (hasVariants && !hasVariants.checked) {
        console.log('Enabling has_variants...');
        hasVariants.checked = true;
        hasVariants.dispatchEvent(new Event('change'));
    }
    
    // Select some sizes and colors
    const firstSize = document.querySelector('.variant-size');
    const secondSize = document.querySelectorAll('.variant-size')[1];
    const firstColor = document.querySelector('.variant-color');
    const secondColor = document.querySelectorAll('.variant-color')[1];
    
    if (firstSize) {
        firstSize.checked = true;
        console.log('Selected size:', firstSize.value);
    }
    if (secondSize) {
        secondSize.checked = true;
        console.log('Selected size:', secondSize.value);
    }
    if (firstColor) {
        firstColor.checked = true;
        console.log('Selected color:', firstColor.value);
    }
    if (secondColor) {
        secondColor.checked = true;
        console.log('Selected color:', secondColor.value);
    }
    
    // Click generate button
    setTimeout(() => {
        const generateBtn = document.getElementById('generate-variants');
        if (generateBtn) {
            console.log('Clicking generate variants button...');
            generateBtn.click();
        } else {
            console.error('Generate variants button not found!');
        }
    }, 1000);
};

// Generate variants table function
window.generateVariantsTable = function(sizes, colors) {
    console.log('generateVariantsTable called with:', { sizes: sizes, colors: colors });
    
    let tableHTML = '';
    tableHTML += '<div class="d-flex justify-content-between align-items-center mb-3">';
    tableHTML += '    <div class="bulk-actions" style="display: none;">';
    tableHTML += '        <button type="button" class="btn btn-outline-danger btn-sm" id="delete-selected-variants">';
    tableHTML += '            <i class="fas fa-trash me-2"></i>Xóa đã chọn (<span id="selected-count">0</span>)';
    tableHTML += '        </button>';
    tableHTML += '    </div>';
    tableHTML += '    <div class="text-muted">';
    tableHTML += '        <small>Tổng <span id="variants-count"></span> biến thể</small>';
    tableHTML += '    </div>';
    tableHTML += '</div>';
    tableHTML += '<div class="table-responsive">';
    tableHTML += '    <table class="table table-bordered" id="variants-table">';
    tableHTML += '        <thead class="table-light">';
    tableHTML += '            <tr>';
    tableHTML += '                <th width="40">';
    tableHTML += '                    <div class="form-check">';
    tableHTML += '                        <input class="form-check-input" type="checkbox" id="select-all-variants">';
    tableHTML += '                        <label class="form-check-label" for="select-all-variants">';
    tableHTML += '                            <span class="visually-hidden">Chọn tất cả</span>';
    tableHTML += '                        </label>';
    tableHTML += '                    </div>';
    tableHTML += '                </th>';
    tableHTML += '                <th>Size</th>';
    tableHTML += '                <th>Màu sắc</th>';
    tableHTML += '                <th>Giá bán (₫)</th>';
    tableHTML += '                <th>Giá sale (₫)</th>';
    tableHTML += '                <th>Tồn kho</th>';
    tableHTML += '                <th>SKU</th>';
    tableHTML += '                <th>Trạng thái</th>';
    tableHTML += '                <th>Thao tác</th>';
    tableHTML += '            </tr>';
    tableHTML += '        </thead>';
    tableHTML += '        <tbody>';

    let variantIndex = 0;
    const basePrice = document.getElementById('price') ? document.getElementById('price').value.replace(/[^\d]/g, '') : '0';
    const productSKU = document.getElementById('sku').value || 'PROD';

    // Keep existing variants first
    const existingTable = document.querySelector('#variants-table tbody');
    if (existingTable) {
        const existingRows = existingTable.querySelectorAll('tr');
        existingRows.forEach(function(row) {
            // Update the index in form names
            row.querySelectorAll('input, select').forEach(function(input) {
                if (input.name && input.name.includes('[')) {
                    input.name = input.name.replace(/\[\d+\]/, '[' + variantIndex + ']');
                }
            });
            tableHTML += row.outerHTML;
            variantIndex++;
        });
    }

    // Add new variants
    sizes.forEach(function(size) {
        colors.forEach(function(color) {
            // Check if this combination already exists
            let exists = false;
            if (existingTable) {
                const existingRows = Array.from(existingTable.querySelectorAll('tr'));
                exists = existingRows.some(function(row) {
                    const sizeInput = row.querySelector('input[name*="[size]"]');
                    const colorInput = row.querySelector('input[name*="[color]"]');
                    return sizeInput && colorInput && 
                           sizeInput.value === size && colorInput.value === color.name;
                });
            }

            if (!exists) {
                console.log('Adding new variant: ' + size + ' - ' + color.name);
                const variantSKU = productSKU + '-' + size.toUpperCase() + '-' + color.name.toUpperCase().replace(/\s+/g, '');
                const formattedPrice = basePrice ? parseInt(basePrice).toLocaleString('vi-VN') : '0';
                
                tableHTML += '<tr>';
                tableHTML += '    <td>';
                tableHTML += '        <div class="form-check">';
                tableHTML += '            <input class="form-check-input variant-checkbox" type="checkbox">';
                tableHTML += '        </div>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <input type="text" class="form-control form-control-sm" name="variants[' + variantIndex + '][size]" value="' + size + '" required>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <input type="text" class="form-control form-control-sm" name="variants[' + variantIndex + '][color]" value="' + color.name + '" required>';
                tableHTML += '        <input type="hidden" name="variants[' + variantIndex + '][color_code]" value="' + color.code + '">';
                tableHTML += '        <div class="d-flex align-items-center mt-1">';
                tableHTML += '            <span class="color-preview me-2" style="width: 16px; height: 16px; border-radius: 50%; background-color: ' + color.code + '; border: 1px solid #ddd;"></span>';
                tableHTML += '            <small class="text-muted">' + color.name + '</small>';
                tableHTML += '        </div>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <div class="input-group input-group-sm">';
                tableHTML += '            <input type="text" class="form-control price-input" name="variants[' + variantIndex + '][price]" value="' + formattedPrice + '" required>';
                tableHTML += '            <span class="input-group-text">₫</span>';
                tableHTML += '        </div>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <div class="input-group input-group-sm">';
                tableHTML += '            <input type="text" class="form-control price-input" name="variants[' + variantIndex + '][sale_price]" value="" placeholder="Tùy chọn">';
                tableHTML += '            <span class="input-group-text">₫</span>';
                tableHTML += '        </div>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <input type="number" class="form-control form-control-sm" name="variants[' + variantIndex + '][stock_quantity]" value="0" min="0" required>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <input type="text" class="form-control form-control-sm" name="variants[' + variantIndex + '][sku]" value="' + variantSKU + '" required>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <div class="form-check form-switch">';
                tableHTML += '            <input class="form-check-input" type="checkbox" name="variants[' + variantIndex + '][is_active]" value="1" checked>';
                tableHTML += '        </div>';
                tableHTML += '    </td>';
                tableHTML += '    <td>';
                tableHTML += '        <button type="button" class="btn btn-sm btn-outline-danger remove-variant">';
                tableHTML += '            <i class="fas fa-trash"></i>';
                tableHTML += '        </button>';
                tableHTML += '    </td>';
                tableHTML += '</tr>';
                variantIndex++;
            }
        });
    });

    tableHTML += '        </tbody>';
    tableHTML += '    </table>';
    tableHTML += '</div>';

    const variantsTableContainer = document.getElementById('variants-table-container');
    if (variantsTableContainer) {
        variantsTableContainer.innerHTML = tableHTML;
        console.log('Variants table updated, total variants:', variantIndex);
        
        // Update variants count
        const variantsCount = document.getElementById('variants-count');
        if (variantsCount) {
            variantsCount.textContent = variantIndex;
        }

        // Add basic remove listeners
        document.querySelectorAll('.remove-variant').forEach(function(button) {
            button.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa biến thể này?')) {
                    this.closest('tr').remove();
                    console.log('Variant removed');
                }
            });
        });
        
    } else {
        console.error('variants-table-container not found!');
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit product variants script initialized');
    
    // Setup has_variants toggle
    const hasVariantsCheckbox = document.getElementById('has_variants');
    const simpleProductSection = document.getElementById('simple-product-section');
    const variantsSection = document.getElementById('variants-section');
    
    if (hasVariantsCheckbox) {
        hasVariantsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                if (simpleProductSection) simpleProductSection.style.display = 'none';
                if (variantsSection) variantsSection.style.display = 'block';
                console.log('Switched to variants mode');
            } else {
                if (simpleProductSection) simpleProductSection.style.display = 'block';
                if (variantsSection) variantsSection.style.display = 'none';
                console.log('Switched to simple mode');
            }
        });
    }
    
    // Setup generate variants button
    const generateVariantsBtn = document.getElementById('generate-variants');
    if (generateVariantsBtn) {
        generateVariantsBtn.addEventListener('click', function() {
            console.log('Generate variants button clicked!');
            
            const selectedSizes = Array.from(document.querySelectorAll('.variant-size:checked')).map(function(cb) {
                return cb.value;
            });
            const selectedColors = Array.from(document.querySelectorAll('.variant-color:checked')).map(function(cb) {
                return {
                    name: cb.value,
                    code: cb.dataset.colorCode
                };
            });

            console.log('Selected sizes:', selectedSizes);
            console.log('Selected colors:', selectedColors);

            if (selectedSizes.length === 0 && selectedColors.length === 0) {
                alert('Vui lòng chọn ít nhất 1 size hoặc 1 màu sắc để tạo biến thể.');
                return;
            }

            // If only sizes selected, use default color
            if (selectedSizes.length > 0 && selectedColors.length === 0) {
                selectedColors.push({ name: 'Mặc định', code: '#ffffff' });
            }
            
            // If only colors selected, use default size
            if (selectedColors.length > 0 && selectedSizes.length === 0) {
                selectedSizes.push('OneSize');
            }

            generateVariantsTable(selectedSizes, selectedColors);
        });
    }

    // Simple price formatting for main fields
    function addPriceFormattingToInput(input) {
        if (!input) return;
        
        input.addEventListener('input', function() {
            const cursorPosition = this.selectionStart;
            const oldLength = this.value.length;
            const unformatted = this.value.replace(/[^\d]/g, '');
            const formatted = unformatted ? parseInt(unformatted).toLocaleString('vi-VN') : '';
            
            this.value = formatted;
            
            const newLength = formatted.length;
            const newPosition = cursorPosition + (newLength - oldLength);
            this.setSelectionRange(newPosition, newPosition);
        });

        input.addEventListener('blur', function() {
            const unformatted = this.value.replace(/[^\d]/g, '');
            this.value = unformatted ? parseInt(unformatted).toLocaleString('vi-VN') : '';
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
    }

    // Form submission handler to convert formatted prices back to numbers
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            document.querySelectorAll('.price-input').forEach(function(input) {
                if (input.value) {
                    input.value = input.value.replace(/[^\d]/g, '');
                }
            });
        });
    }
    
    // Auto-announce functions are ready
    setTimeout(function() {
        console.log('=== EDIT FORM DEBUG FUNCTIONS READY ===');
        console.log('Available functions:');
        console.log('- debugEditForm()');
        console.log('- testEditVariantGeneration()');
        console.log('- generateVariantsTable(sizes, colors)');
    }, 1000);
});

console.log('Edit product variants inline script loaded successfully!');
</script>
@endpush
