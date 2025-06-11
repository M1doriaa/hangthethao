@extends('admin.layout')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-box me-2"></i>Quản lý sản phẩm
    </h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Tên sản phẩm, SKU, danh mục...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Danh mục</label>
                <select class="form-select" name="category">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $slug => $name)
                        <option value="{{ $slug }}" {{ request('category') == $slug ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" name="status">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Danh sách sản phẩm ({{ $products->total() }} sản phẩm)
        </h5>
        <div class="d-flex gap-2">
            @if(request()->hasAny(['search', 'category', 'status']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                </a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 80px;">Ảnh</th>
                            <th>Sản phẩm</th>
                            <th style="width: 120px;">Giá</th>
                            <th style="width: 100px;">Tồn kho</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th style="width: 120px;">Ngày tạo</th>
                            <th style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <img src="{{ $product->main_image }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <small class="text-muted">
                                        SKU: {{ $product->sku }} | 
                                        {{ ucfirst($product->category) }}
                                        @if($product->brand)
                                            | {{ $product->brand }}
                                        @endif
                                    </small>
                                    @if($product->is_featured)
                                        <br><span class="badge bg-warning text-dark">Nổi bật</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-danger">{{ $product->formatted_price }}</div>
                                @if($product->original_price)
                                    <small class="text-muted text-decoration-line-through">
                                        {{ $product->formatted_original_price }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($product->stock_quantity > 10)
                                    <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="badge bg-warning text-dark">{{ $product->stock_quantity }}</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </td>
                            <td>
                                @if($product->status === 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($product->status === 'inactive')
                                    <span class="badge bg-secondary">Tạm dừng</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $product->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $product->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Xóa"
                                            onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không tìm thấy sản phẩm nào</h5>
                @if(request()->hasAny(['search', 'category', 'status']))
                    <p class="text-muted">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Xóa bộ lọc
                    </a>
                @else
                    <p class="text-muted">Tạo sản phẩm đầu tiên của bạn</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
                    </a>
                @endif
            </div>
        @endif
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
