@php
    $approvedReviews = $product->reviews ? $product->reviews->where('is_approved', true) : collect();
    $approvedReviewsCount = $approvedReviews->count();
    $averageRating = $approvedReviews->avg('rating') ?? 0;
@endphp

@if($approvedReviewsCount > 0)
<div class="reviews-section mt-5">    <h4 class="mb-4">
        <i class="fas fa-star text-warning me-2"></i>
        Đánh giá sản phẩm ({{ $approvedReviewsCount }})
    </h4>
    
    <div class="reviews-summary mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="display-4 text-warning">{{ number_format($averageRating, 1) }}</div>
                    <div class="text-muted">trên 5 sao</div>
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $averageRating)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @php
                    $totalReviews = $approvedReviewsCount;
                @endphp                @for($rating = 5; $rating >= 1; $rating--)
                    @php
                        $count = $approvedReviews->where('rating', $rating)->count();
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    @endphp
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2">{{ $rating }} sao</span>
                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-muted small">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>    <div class="reviews-list">
        @foreach($approvedReviews->sortByDesc('created_at')->take(5) as $review)
            <div class="review-item border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                @if($review->user->avatar)
                                    <img src="{{ asset('storage/' . $review->user->avatar) }}" 
                                         alt="{{ $review->user->name }}" 
                                         class="rounded-circle"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px; font-size: 18px;">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $review->user->name }}</h6>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="mb-1">{{ $review->comment }}</p>
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    @auth
                        @if($review->user_id == auth()->id())
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item" onclick="editReview({{ $review->id }}, {{ $review->rating }}, '{{ $review->comment }}')">
                                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                        </button>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" 
                                                    onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                <i class="fas fa-trash me-2"></i>Xóa
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
        
        @if($approvedReviewsCount > 5)
            <div class="text-center">
                <button class="btn btn-outline-primary" onclick="loadMoreReviews()">
                    Xem thêm đánh giá
                </button>
            </div>
        @endif
    </div>
</div>
@endif

<!-- Review Form -->
@auth
    @php
        $userReview = $product->reviews ? $product->reviews->where('user_id', auth()->id())->first() : null;
    @endphp
    
    <div class="review-form-section mt-5">
        <div class="review-form-card">
            <div class="review-form-header">
                <h4 class="mb-0">
                    <i class="fas fa-pencil-alt me-2"></i>
                    {{ $userReview ? 'Cập nhật đánh giá' : 'Viết đánh giá' }}
                </h4>
                <p class="text-muted mt-2 mb-0">Chia sẻ trải nghiệm của bạn với sản phẩm này</p>
            </div>
            
            @if($userReview)
                @if(!$userReview->is_approved)
                    <div class="alert alert-info alert-custom">
                        <i class="fas fa-clock me-2"></i>
                        Đánh giá của bạn đang chờ duyệt.
                    </div>
                @endif
            @endif
            
            <form method="POST" action="{{ $userReview ? route('reviews.update', $userReview) : route('reviews.store', $product) }}" id="reviewForm" class="review-form-body">
                @csrf
                @if($userReview)
                    @method('PUT')
                @endif
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Đánh giá của bạn <span class="text-danger">*</span></label>
                    <div class="rating-container">
                        <div class="rating-input">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" 
                                       {{ old('rating', $userReview?->rating) == $i ? 'checked' : '' }} required>
                                <label for="star{{ $i }}" class="star-label" data-rating="{{ $i }}">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
                        </div>
                        <span class="rating-text ms-3">
                            <span id="rating-text-value">Chọn đánh giá</span>
                        </span>
                    </div>
                    @error('rating')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="comment" class="form-label fw-bold">Nhận xét <span class="text-danger">*</span></label>
                    <div class="comment-wrapper">
                        <textarea class="form-control comment-textarea @error('comment') is-invalid @enderror" 
                                  id="comment" 
                                  name="comment" 
                                  rows="5" 
                                  placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." 
                                  required>{{ old('comment', $userReview?->comment) }}</textarea>
                        <div class="character-count">
                            <span id="char-count">0</span>/500
                        </div>
                    </div>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>
                        {{ $userReview ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}
                    </button>
                    @if($userReview)
                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="cancelEdit()">
                            Hủy
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@else
    <div class="review-login mt-5">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <a href="{{ route('login') }}" class="alert-link">Đăng nhập</a> để có thể đánh giá sản phẩm.
        </div>
    </div>
@endauth

<style>
/* Review Form Styling */
.review-form-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

.review-form-card:hover {
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
}

.review-form-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e9ecef;
}

.review-form-header h4 {
    color: #333;
    font-weight: 600;
}

.review-form-body {
    padding: 2rem;
}

/* Alert Custom */
.alert-custom {
    border-radius: 8px;
    margin: 1.5rem 2rem 0;
    border: none;
    font-size: 0.95rem;
}

/* Rating Container */
.rating-container {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.rating-container:hover {
    border-color: #e9ecef;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star-label {
    cursor: pointer;
    font-size: 2rem;
    color: #dee2e6;
    margin: 0 5px;
    transition: all 0.2s ease;
    transform: scale(1);
}

.rating-input .star-label:hover {
    transform: scale(1.2);
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input .star-label:hover,
.rating-input .star-label:hover ~ .star-label {
    color: #ffc107;
}

.rating-text {
    font-size: 1rem;
    color: #6c757d;
    min-width: 120px;
}

#rating-text-value {
    font-weight: 500;
    color: #495057;
}

/* Comment Textarea */
.comment-wrapper {
    position: relative;
}

.comment-textarea {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    font-size: 1rem;
    resize: vertical;
    transition: all 0.3s ease;
    background: #fafbfc;
}

.comment-textarea:focus {
    border-color: var(--primary-red);
    background: #ffffff;
    box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.15);
}

.character-count {
    position: absolute;
    bottom: 10px;
    right: 15px;
    font-size: 0.85rem;
    color: #6c757d;
    background: rgba(255, 255, 255, 0.9);
    padding: 2px 8px;
    border-radius: 4px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-submit {
    background: var(--primary-red);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    background: var(--secondary-red);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .review-form-header,
    .review-form-body {
        padding: 1.25rem;
    }
    
    .rating-input .star-label {
        font-size: 1.5rem;
        margin: 0 3px;
    }
    
    .rating-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .rating-text {
        margin-left: 0 !important;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-submit {
        width: 100%;
    }
}

/* Reviews Section Improvements */
.reviews-section {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.review-item {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef !important;
}

.review-item:hover {
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
}

/* Animation for star rating */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.rating-input .star-label:active {
    animation: pulse 0.3s ease;
}
</style>

<script>
function editReview(reviewId, rating, comment) {
    // Populate form with existing review data
    document.querySelector(`input[name="rating"][value="${rating}"]`).checked = true;
    document.getElementById('comment').value = comment;
    
    // Change form action to update
    document.getElementById('reviewForm').action = `/reviews/${reviewId}`;
    
    // Add method spoofing for PUT
    let methodInput = document.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        document.getElementById('reviewForm').appendChild(methodInput);
    }
    methodInput.value = 'PUT';
    
    // Scroll to form
    document.getElementById('reviewForm').scrollIntoView({ behavior: 'smooth' });
}

function loadMoreReviews() {
    // Implementation for loading more reviews via AJAX
    console.log('Load more reviews...');
}
</script>
