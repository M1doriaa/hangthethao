@extends('layouts.app')

@section('title', 'Giới thiệu - Hang The Thao')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Giới thiệu</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4">Giới thiệu về Hang The Thao</h1>
            
            <div class="mb-4">
                <h3 class="text-danger mb-3">Câu chuyện của chúng tôi</h3>
                <p>Hang The Thao được thành lập với niềm đam mê bóng đá và mong muốn mang đến cho người hâm mộ những sản phẩm chất lượng cao với giá cả hợp lý. Chúng tôi hiểu rằng một chiếc áo bóng đá không chỉ là trang phục, mà còn là biểu tượng của tình yêu, niềm tự hào và sự gắn kết với đội bóng yêu thích.</p>
                
                <p>Từ những ngày đầu khiêm tốn, chúng tôi đã không ngừng phát triển và mở rộng, luôn đặt chất lượng sản phẩm và sự hài lòng của khách hàng lên hàng đầu. Với đội ngũ nhân viên có kinh nghiệm và am hiểu sâu về thể thao, chúng tôi tự tin mang đến cho bạn những trải nghiệm mua sắm tuyệt vời nhất.</p>
            </div>
            
            <div class="mb-4">
                <h3 class="text-danger mb-3">Sứ mệnh của chúng tôi</h3>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-danger me-2"></i>Cung cấp áo bóng đá chính hãng, chất lượng cao từ các thương hiệu uy tín</li>
                    <li class="mb-2"><i class="fas fa-check text-danger me-2"></i>Mang đến dịch vụ khách hàng tận tâm và chuyên nghiệp</li>
                    <li class="mb-2"><i class="fas fa-check text-danger me-2"></i>Xây dựng cộng đồng những người yêu bóng đá tại Việt Nam</li>
                    <li class="mb-2"><i class="fas fa-check text-danger me-2"></i>Liên tục cập nhật và đa dạng hóa sản phẩm theo xu hướng</li>
                </ul>
            </div>
            
            <div class="mb-4">
                <h3 class="text-danger mb-3">Tại sao chọn Hang The Thao?</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-medal fa-3x text-danger mb-3"></i>
                                <h5>Chất lượng đảm bảo</h5>
                                <p class="text-muted">Tất cả sản phẩm đều được kiểm tra kỹ lưỡng trước khi giao đến tay khách hàng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-shipping-fast fa-3x text-danger mb-3"></i>
                                <h5>Giao hàng nhanh chóng</h5>
                                <p class="text-muted">Giao hàng toàn quốc, nhanh chóng và an toàn</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-headset fa-3x text-danger mb-3"></i>
                                <h5>Hỗ trợ 24/7</h5>
                                <p class="text-muted">Đội ngũ tư vấn nhiệt tình, sẵn sàng hỗ trợ mọi lúc</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x text-danger mb-3"></i>
                                <h5>Giá cả hợp lý</h5>
                                <p class="text-muted">Cam kết giá cả cạnh tranh nhất thị trường</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin liên hệ</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="fas fa-map-marker-alt text-danger me-2"></i>Địa chỉ:</strong><br>
                        123 Đường ABC, Quận XYZ<br>
                        TP. Hồ Chí Minh, Việt Nam
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-phone text-danger me-2"></i>Điện thoại:</strong><br>
                        0849 84 48 85
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-envelope text-danger me-2"></i>Email:</strong><br>
                        jklamn666@gmail.com
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-clock text-danger me-2"></i>Giờ làm việc:</strong><br>
                        Thứ 2 - Chủ nhật: 8:00 - 22:00
                    </div>                    <div class="text-center mt-4">
                        <a href="/contact" class="btn btn-danger">
                            <i class="fas fa-paper-plane me-2"></i>Liên hệ ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .breadcrumb {
        background: none;
        padding: 0;
        margin-bottom: 2rem;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #6c757d;
    }
    
    .breadcrumb-item a {
        color: #C41E3A;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        text-decoration: underline;
    }
</style>
@endpush
