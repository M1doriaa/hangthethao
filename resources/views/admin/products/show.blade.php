@extends('admin.layout')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-eye me-2"></i>Chi tiết sản phẩm
    </h1>
    <div class="btn-group">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <!-- Product Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Thông tin sản phẩm
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ $product->main_image }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded">
                    </div>
                    <div class="col-md-9">
                        <h3 class="mb-3">{{ $product->name }}</h3>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>SKU:</strong></div>
                            <div class="col-sm-9">{{ $product->sku }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Danh mục:</strong></div>
                            <div class="col-sm-9">
                                <span class="badge bg-primary">{{ ucfirst($product->category) }}</span>
                            </div>
                        </div>
                        
                        @if($product->brand)
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Thương hiệu:</strong></div>
                            <div class="col-sm-9">{{ $product->brand }}</div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Giá bán:</strong></div>
                            <div class="col-sm-9">
                                <span class="h5 text-danger">{{ $product->formatted_price }}</span>
                                @if($product->original_price)
                                    <span class="text-muted text-decoration-line-through ms-2">
                                        {{ $product->formatted_original_price }}
                                    </span>
                                    <span class="badge bg-success ms-2">
                                        Giảm {{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Tồn kho:</strong></div>
                            <div class="col-sm-9">
                                @if($product->stock_quantity > 10)
                                    <span class="badge bg-success">{{ $product->stock_quantity }} sản phẩm</span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="badge bg-warning text-dark">{{ $product->stock_quantity }} sản phẩm</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Trạng thái:</strong></div>
                            <div class="col-sm-9">
                                @if($product->status === 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($product->status === 'inactive')
                                    <span class="badge bg-secondary">Tạm dừng</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                                
                                @if($product->is_featured)
                                    <span class="badge bg-warning text-dark ms-1">Nổi bật</span>
                                @endif
                                
                                @if(!$product->is_active)
                                    <span class="badge bg-dark ms-1">Không hoạt động</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($product->description)
                <div class="mt-4">
                    <h6><strong>Mô tả sản phẩm:</strong></h6>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Variants -->
        @if($product->sizes || $product->colors)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-palette me-2"></i>Biến thể sản phẩm
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($product->sizes)
                    <div class="col-md-6">
                        <h6><strong>Kích thước:</strong></h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->sizes as $size)
                                <span class="badge bg-outline-primary border">{{ $size }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($product->colors)
                    <div class="col-md-6">
                        <h6><strong>Màu sắc:</strong></h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->colors as $color)
                                <span class="badge bg-outline-secondary border">{{ $color }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- SEO Information -->
        @if($product->meta_title || $product->meta_description)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-search me-2"></i>Thông tin SEO
                </h5>
            </div>
            <div class="card-body">
                @if($product->meta_title)
                <div class="mb-3">
                    <h6><strong>Meta Title:</strong></h6>
                    <p class="text-muted">{{ $product->meta_title }}</p>
                </div>
                @endif
                
                @if($product->meta_description)
                <div class="mb-3">
                    <h6><strong>Meta Description:</strong></h6>
                    <p class="text-muted">{{ $product->meta_description }}</p>
                </div>
                @endif
                
                <div class="mb-0">
                    <h6><strong>URL slug:</strong></h6>
                    <code>{{ url('/product/' . $product->slug) }}</code>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Thống kê
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="h4 text-primary">{{ $product->rating }}</div>
                            <small class="text-muted">Đánh giá</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success">{{ $product->reviews_count }}</div>
                        <small class="text-muted">Lượt đánh giá</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm
                    </a>
                    
                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-success" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Xem trên website
                    </a>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                        <i class="fas fa-trash me-2"></i>Xóa sản phẩm
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info me-2"></i>Thông tin hệ thống
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Slug:</strong></td>
                        <td><code>{{ $product->slug }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Ngày tạo:</strong></td>
                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Cập nhật:</strong></td>
                        <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="productName"></strong>?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(productId, productName) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = `/admin/products/${productId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
