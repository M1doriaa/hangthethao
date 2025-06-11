@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
                    
                    <p class="lead mb-4">
                        Cảm ơn bạn đã mua hàng tại <strong style="color: #C41E3A;">Hang Thể Thao</strong>. 
                        Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
                    </p>
                    
                    @if($order)
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Thời gian đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong>Trạng thái:</strong> <span class="badge bg-warning">{{ $order->status_label }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Phương thức thanh toán:</strong> {{ $order->payment_method_label }}</p>
                                <p class="mb-1"><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ $order->formatted_total }}</span></p>
                                <p class="mb-1"><strong>Số lượng sản phẩm:</strong> {{ $order->total_items }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết sản phẩm đã đặt -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Sản phẩm đã đặt</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Size</th>
                                            <th>Màu</th>
                                            <th>Giá</th>
                                            <th>SL</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                        <tr>
                                            <td class="text-start">
                                                <div class="d-flex align-items-center">
                                                    @if($item->product_image)
                                                    <img src="{{ asset('storage/' . $item->product_image) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    {{ $item->product_name }}
                                                </div>
                                            </td>
                                            <td>{{ $item->size ?: 'N/A' }}</td>
                                            <td>{{ $item->color ?: 'N/A' }}</td>
                                            <td>{{ $item->formatted_price }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="fw-bold">{{ $item->formatted_total }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Tạm tính:</strong></td>
                                            <td class="fw-bold">{{ $order->formatted_subtotal }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                            <td class="fw-bold">{{ $order->formatted_shipping_fee }}</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                                            <td class="fw-bold text-danger">{{ $order->formatted_total }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                        <p class="mb-1"><strong>Thời gian đặt:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>Trạng thái:</strong> Đang xử lý</p>
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5>Chúng tôi sẽ:</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-envelope text-primary me-2"></i>Gửi email xác nhận đơn hàng</li>
                            <li class="mb-2"><i class="fas fa-phone text-primary me-2"></i>Liên hệ xác nhận thông tin giao hàng</li>
                            <li class="mb-2"><i class="fas fa-truck text-primary me-2"></i>Giao hàng trong 2-3 ngày làm việc</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-md-2" style="background-color: #C41E3A; border-color: #C41E3A;">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                        <a href="{{ route('category.index', 'ao-clb') }}" class="btn btn-outline-primary btn-lg" style="border-color: #C41E3A; color: #C41E3A;">
                            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
