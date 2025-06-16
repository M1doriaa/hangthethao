@extends('admin.layout')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star me-2"></i>
                        Quản lý đánh giá sản phẩm
                    </h3>
                </div>

                <!-- Statistics Cards -->
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-2x mb-2"></i>
                                    <h4>{{ $reviewStats['total'] }}</h4>
                                    <p class="mb-0">Tổng đánh giá</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h4>{{ $reviewStats['approved'] }}</h4>
                                    <p class="mb-0">Đã duyệt</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h4>{{ $reviewStats['pending'] }}</h4>
                                    <p class="mb-0">Chờ duyệt</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-star fa-2x mb-2"></i>
                                    <h4>{{ number_format($reviewStats['average_rating'], 1) }}</h4>
                                    <p class="mb-0">Điểm TB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="rating" class="form-select" onchange="this.form.submit()">
                                    <option value="">Tất cả rating</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo nội dung, tên khách hàng, sản phẩm..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Tìm
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    <form id="bulkActionForm" method="POST" action="{{ route('admin.reviews.bulk-action') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select name="action" class="form-select" required>
                                        <option value="">Chọn hành động</option>
                                        <option value="approve">Duyệt đánh giá</option>
                                        <option value="reject">Từ chối đánh giá</option>
                                        <option value="delete">Xóa đánh giá</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">Thực hiện</button>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    Đã chọn: <span id="selectedCount">0</span> đánh giá
                                </small>
                            </div>
                        </div>

                        <!-- Reviews Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Khách hàng</th>
                                        <th>Sản phẩm</th>
                                        <th>Rating</th>
                                        <th>Nội dung</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reviews as $review)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="review_ids[]" value="{{ $review->id }}" class="form-check-input review-checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($review->user->avatar)
                                                        <img src="{{ asset('storage/' . $review->user->avatar) }}" 
                                                             alt="{{ $review->user->name }}" 
                                                             class="rounded-circle me-2"
                                                             style="width: 30px; height: 30px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                             style="width: 30px; height: 30px; font-size: 12px;">
                                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $review->user->name }}</div>
                                                        <small class="text-muted">{{ $review->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($review->product->images->first())
                                                        <img src="{{ asset('storage/' . $review->product->images->first()->image_path) }}" 
                                                             alt="{{ $review->product->name }}" 
                                                             class="rounded me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $review->product->name }}</div>
                                                        <small class="text-muted">ID: {{ $review->product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                <small class="text-muted">{{ $review->rating }}/5</small>
                                            </td>
                                            <td>
                                                <div style="max-width: 200px;">
                                                    {{ Str::limit($review->comment, 100) }}
                                                    @if(strlen($review->comment) > 100)
                                                        <a href="{{ route('admin.reviews.show', $review) }}" class="text-primary">...xem thêm</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($review->is_approved)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Đã duyệt
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock"></i> Chờ duyệt
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $review->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($review->is_approved)
                                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Từ chối">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Duyệt">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Chưa có đánh giá nào</h5>
                                                <p class="text-muted">Khách hàng sẽ có thể đánh giá sản phẩm sau khi mua hàng.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Pagination -->
                    @if($reviews->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Hiển thị {{ $reviews->firstItem() }}-{{ $reviews->lastItem() }} 
                                trong tổng số {{ $reviews->total() }} đánh giá
                            </div>
                            {{ $reviews->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
    const selectedCountElement = document.getElementById('selectedCount');
    const bulkActionForm = document.getElementById('bulkActionForm');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        reviewCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox change
    reviewCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateSelectedCount();
        });
    });

    function updateSelectAllState() {
        const checkedCount = document.querySelectorAll('.review-checkbox:checked').length;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < reviewCheckboxes.length;
        selectAllCheckbox.checked = checkedCount === reviewCheckboxes.length;
    }

    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.review-checkbox:checked').length;
        selectedCountElement.textContent = selectedCount;
    }

    // Bulk action form submission
    bulkActionForm.addEventListener('submit', function(e) {
        const selectedReviews = document.querySelectorAll('.review-checkbox:checked');
        const action = document.querySelector('select[name="action"]').value;

        if (selectedReviews.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một đánh giá để thực hiện hành động.');
            return;
        }

        if (!action) {
            e.preventDefault();
            alert('Vui lòng chọn hành động để thực hiện.');
            return;
        }

        let message = '';
        switch(action) {
            case 'approve':
                message = `Bạn có chắc muốn duyệt ${selectedReviews.length} đánh giá đã chọn?`;
                break;
            case 'reject':
                message = `Bạn có chắc muốn từ chối ${selectedReviews.length} đánh giá đã chọn?`;
                break;
            case 'delete':
                message = `Bạn có chắc muốn xóa ${selectedReviews.length} đánh giá đã chọn? Hành động này không thể hoàn tác.`;
                break;
        }

        if (!confirm(message)) {
            e.preventDefault();
        }
    });

    // Initialize counts
    updateSelectedCount();
    updateSelectAllState();
});
</script>
@endsection
