@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </h1>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Thống kê sản phẩm -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-number">{{ $stats['total_products'] }}</div>
                <div class="stats-label">Tổng sản phẩm</div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-number">{{ $stats['active_products'] }}</div>
                <div class="stats-label">Đang hoạt động</div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-warning">
            <div class="card-body text-center text-warning">
                <div class="stats-number">{{ $stats['out_of_stock'] }}</div>
                <div class="stats-label">Hết hàng</div>
            </div>
        </div>
    </div>
    
    <!-- Thống kê đơn hàng -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ $stats['total_orders'] }}</div>
                <div class="stats-label">Tổng đơn hàng</div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ $stats['pending_orders'] }}</div>
                <div class="stats-label">Chờ xác nhận</div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ number_format($stats['total_revenue'], 0, ',', '.') }}đ</div>
                <div class="stats-label">Doanh thu</div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê đơn hàng theo trạng thái -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Thống kê đơn hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <div class="text-warning">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h4>{{ $stats['pending_orders'] }}</h4>
                            <small>Chờ xác nhận</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="text-info">
                            <i class="fas fa-check fa-2x mb-2"></i>
                            <h4>{{ $stats['confirmed_orders'] }}</h4>
                            <small>Đã xác nhận</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="text-primary">
                            <i class="fas fa-truck fa-2x mb-2"></i>
                            <h4>{{ $stats['shipping_orders'] }}</h4>
                            <small>Đang vận chuyển</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h4>{{ $stats['delivered_orders'] }}</h4>
                            <small>Đã giao hàng</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="text-danger">
                            <i class="fas fa-times fa-2x mb-2"></i>
                            <h4>{{ $stats['cancelled_orders'] }}</h4>
                            <small>Đã hủy</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="text-dark">
                            <i class="fas fa-calendar-day fa-2x mb-2"></i>
                            <h4>{{ $stats['today_orders'] }}</h4>
                            <small>Hôm nay</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Products -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Sản phẩm mới nhất
                </h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-light">
                    Xem tất cả
                </a>
            </div>
            <div class="card-body">
                @if($recent_products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->main_image }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold">{{ Str::limit($product->name, 30) }}</div>
                                                <small class="text-muted">{{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->formatted_price }}</td>
                                    <td>
                                        @if($product->status === 'active')
                                            <span class="badge bg-success">Hoạt động</span>
                                        @elseif($product->status === 'inactive')
                                            <span class="badge bg-secondary">Tạm dừng</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box fa-3x mb-3"></i>
                        <p>Chưa có sản phẩm nào</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Đơn hàng gần đây
                </h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-light">
                    Xem tất cả
                </a>
            </div>
            <div class="card-body">
                @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                            <strong class="text-primary">{{ $order->order_number }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ Str::limit($order->customer_name, 20) }}</div>
                                            <small class="text-muted">{{ $order->total_items }} sản phẩm</small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-danger">{{ $order->formatted_total }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Products -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Sản phẩm sắp hết hàng
                </h5>
                <span class="badge bg-dark">{{ $low_stock_products->count() }}</span>
            </div>
            <div class="card-body">
                @if($low_stock_products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Tồn kho</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($low_stock_products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->main_image }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold">{{ Str::limit($product->name, 25) }}</div>
                                                <small class="text-muted">{{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                        <p>Tất cả sản phẩm đều có đủ hàng</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>
                            Thêm sản phẩm mới
                        </a>
                    </div>                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Quản lý đơn hàng
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-list me-2"></i>
                            Quản lý sản phẩm
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-success w-100" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Xem website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh dashboard every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
});
</script>
@endpush
