@extends('admin.layout')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-file-invoice me-2"></i>
        Chi tiết đơn hàng #{{ $order->order_number }}
    </h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
    </a>
</div>

<div class="row">
    <!-- Thông tin đơn hàng -->
    <div class="col-lg-8">
        <!-- Thông tin chung -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Mã đơn hàng:</strong></td>
                                <td>{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày đặt hàng:</strong></td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }} fs-6">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Phương thức thanh toán:</strong></td>
                                <td>{{ $order->payment_method_label }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($order->confirmed_at)
                            <tr>
                                <td><strong>Thời gian xác nhận:</strong></td>
                                <td>{{ $order->confirmed_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                            @if($order->shipped_at)
                            <tr>
                                <td><strong>Thời gian vận chuyển:</strong></td>
                                <td>{{ $order->shipped_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                            @if($order->delivered_at)
                            <tr>
                                <td><strong>Thời gian giao hàng:</strong></td>
                                <td>{{ $order->delivered_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Thông tin khách hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Thông tin liên hệ</h6>
                        <p class="mb-1"><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Địa chỉ giao hàng</h6>
                        <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                        <p class="mb-1"><strong>Phường/Xã:</strong> {{ $order->ward }}</p>
                        <p class="mb-1"><strong>Quận/Huyện:</strong> {{ $order->district }}</p>
                        <p class="mb-1"><strong>Tỉnh/Thành phố:</strong> {{ $order->city }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-bag me-2"></i>Chi tiết sản phẩm
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Size</th>
                                <th>Màu sắc</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->product)
                                            <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->size ?: 'N/A' }}</td>
                                <td>{{ $item->color ?: 'N/A' }}</td>
                                <td>{{ $item->formatted_price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>{{ $item->formatted_total }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Tóm tắt đơn hàng -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>Tóm tắt đơn hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính:</span>
                    <span>{{ $order->formatted_subtotal }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Phí vận chuyển:</span>
                    <span>{{ $order->formatted_shipping_fee }}</span>
                </div>
                @if($order->tax > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Thuế:</span>
                    <span>{{ number_format($order->tax, 0, ',', '.') }}đ</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between mb-0">
                    <strong>Tổng cộng:</strong>
                    <strong class="text-danger fs-5">{{ $order->formatted_total }}</strong>
                </div>
            </div>
        </div>

        <!-- Cập nhật trạng thái -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Cập nhật trạng thái
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái mới</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Chọn trạng thái</option>
                            @if($order->canBeConfirmed())
                            <option value="confirmed">Đã xác nhận</option>
                            @endif
                            @if($order->status == 'confirmed')
                            <option value="processing">Đang xử lý</option>
                            @endif
                            @if($order->canBeShipped())
                            <option value="shipping">Đang vận chuyển</option>
                            @endif
                            @if($order->canBeDelivered())
                            <option value="delivered">Đã giao hàng</option>
                            @endif
                            @if($order->canBeCancelled())
                            <option value="cancelled">Hủy đơn hàng</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Ghi chú về việc thay đổi trạng thái..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Cập nhật trạng thái
                    </button>
                </form>
            </div>
        </div>

        <!-- Thao tác nhanh -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($order->canBeConfirmed())
                    <button type="button" class="btn btn-success" 
                            onclick="quickUpdateStatus('confirmed')">
                        <i class="fas fa-check me-2"></i>Xác nhận đơn hàng
                    </button>
                    @endif
                    
                    @if($order->canBeShipped())
                    <button type="button" class="btn btn-info" 
                            onclick="quickUpdateStatus('shipping')">
                        <i class="fas fa-truck me-2"></i>Chuyển sang vận chuyển
                    </button>
                    @endif
                    
                    @if($order->canBeDelivered())
                    <button type="button" class="btn btn-success" 
                            onclick="quickUpdateStatus('delivered')">
                        <i class="fas fa-check-circle me-2"></i>Hoàn thành giao hàng
                    </button>
                    @endif
                    
                    @if($order->canBeCancelled())
                    <button type="button" class="btn btn-danger" 
                            onclick="quickUpdateStatus('cancelled')">
                        <i class="fas fa-times me-2"></i>Hủy đơn hàng
                    </button>
                    @endif
                    
                    @if(in_array($order->status, ['pending', 'cancelled']))
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="deleteOrder()">
                        <i class="fas fa-trash me-2"></i>Xóa đơn hàng
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function quickUpdateStatus(status) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng này?')) {
        return;
    }
    
    fetch(`/admin/api/orders/{{ $order->id }}/update-status`, {
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

function deleteOrder() {
    if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Hành động này không thể hoàn tác.')) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.orders.destroy", $order->id) }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
