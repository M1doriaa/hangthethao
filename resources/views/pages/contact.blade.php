@extends('layouts.app')

@section('title', 'Liên hệ - Hang The Thao')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Liên hệ</li>
                </ol>
            </nav>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn cho chúng tôi</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                       id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung tin nhắn <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Thông tin liên hệ</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="fas fa-map-marker-alt text-danger me-2"></i>Địa chỉ:</strong><br>
                        123 Đường ABC, Quận XYZ<br>
                        TP. Hồ Chí Minh, Việt Nam
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-phone text-danger me-2"></i>Điện thoại:</strong><br>
                        <a href="tel:0849844885" class="text-decoration-none">0849 84 48 85</a>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-envelope text-danger me-2"></i>Email:</strong><br>
                        <a href="mailto:jklamn666@gmail.com" class="text-decoration-none">jklamn666@gmail.com</a>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-clock text-danger me-2"></i>Giờ làm việc:</strong><br>
                        Thứ 2 - Chủ nhật: 8:00 - 22:00
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p class="mb-2"><strong>Theo dõi chúng tôi:</strong></p>
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm me-2">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Câu hỏi thường gặp</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Q: Làm sao để biết size áo phù hợp?</strong><br>
                        <small class="text-muted">A: Bạn có thể tham khảo bảng size chi tiết ở mỗi sản phẩm hoặc liên hệ với chúng tôi để được tư vấn.</small>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Q: Thời gian giao hàng bao lâu?</strong><br>
                        <small class="text-muted">A: Thông thường từ 2-5 ngày làm việc tùy theo khu vực.</small>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Q: Có chính sách đổi trả không?</strong><br>
                        <small class="text-muted">A: Có, chúng tôi hỗ trợ đổi trả trong vòng 7 ngày với điều kiện sản phẩm còn nguyên tem mác.</small>
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
    
    .social-links a {
        transition: transform 0.2s ease;
    }
    
    .social-links a:hover {
        transform: scale(1.1);
    }
</style>
@endpush
