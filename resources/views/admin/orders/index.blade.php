@extends('admin.layout')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-shopping-cart me-2"></i>
        Quản lý đơn hàng
    </h1>
</div>

<!-- Thống kê tổng quan -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-number">{{ number_format($stats['total_orders']) }}</div>
                <div class="stats-label">Tổng đơn hàng</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ number_format($stats['pending_orders']) }}</div>
                <div class="stats-label">Chờ xác nhận</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ number_format($stats['shipping_orders']) }}</div>
                <div class="stats-label">Đang vận chuyển</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ number_format($stats['total_revenue'], 0, ',', '.') }}đ</div>
                <div class="stats-label">Doanh thu</div>
            </div>
        </div>
    </div>
</div>

<!-- Bộ lọc và tìm kiếm -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Bộ lọc và tìm kiếm
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Mã đơn hàng, tên, email, SĐT...">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang vận chuyển</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="payment_method" class="form-label">Thanh toán</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                        <option value="">Tất cả</option>
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                        <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_from" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_to" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách đơn hàng -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Danh sách đơn hàng
            <span class="badge bg-secondary ms-2">{{ $orders->total() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $order->order_number }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->customer_name }}</strong><br>
                                <small class="text-muted">{{ $order->customer_phone }}</small><br>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $order->created_at->format('d/m/Y') }}<br>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $order->total_items }} sản phẩm</span>
                        </td>
                        <td>
                            <strong class="text-danger">{{ $order->formatted_total }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->payment_method_label }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($order->canBeCancelled())
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="updateOrderStatus({{ $order->id }}, 'cancelled')" 
                                        title="Hủy đơn hàng">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                                
                                @if($order->canBeConfirmed())
                                <button type="button" class="btn btn-sm btn-outline-success"
                                        onclick="updateOrderStatus({{ $order->id }}, 'confirmed')" 
                                        title="Xác nhận đơn hàng">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                                
                                @if($order->canBeShipped())
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        onclick="updateOrderStatus({{ $order->id }}, 'shipping')" 
                                        title="Chuyển sang vận chuyển">
                                    <i class="fas fa-truck"></i>
                                </button>
                                @endif
                                
                                @if($order->canBeDelivered())
                                <button type="button" class="btn btn-sm btn-outline-success"
                                        onclick="updateOrderStatus({{ $order->id }}, 'delivered')" 
                                        title="Hoàn thành giao hàng">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-shopping-cart text-muted fa-3x mb-3"></i>
                            <p class="text-muted mb-0">Không có đơn hàng nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($orders->hasPages())
    <div class="card-footer">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function updateOrderStatus(orderId, status) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng này?')) {
        return;
    }
    
    fetch(`/admin/api/orders/${orderId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload trang để cập nhật giao diện
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng');
    });
}
</script>
@endpush
