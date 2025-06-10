@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                    
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                        <p class="mb-1"><strong>Mã đơn hàng:</strong> #HT{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</p>
                        <p class="mb-1"><strong>Thời gian đặt:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>Trạng thái:</strong> Đang xử lý</p>
                    </div>
                    
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
                        <a href="{{ route('category', 'ao-clb') }}" class="btn btn-outline-primary btn-lg" style="border-color: #C41E3A; color: #C41E3A;">
                            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
