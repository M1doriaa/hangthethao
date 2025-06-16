<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])
                      ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status == 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search by comment or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(15);
        $reviewStats = $this->getReviewStats();

        return view('admin.reviews.index', compact('reviews', 'reviewStats'));
    }

    /**
     * Show review details
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Đánh giá đã được duyệt thành công!');
    }

    /**
     * Reject/Unapprove a review
     */
    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);

        return back()->with('success', 'Đánh giá đã bị từ chối!');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa thành công!');
    }

    /**
     * Bulk actions for reviews
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        $reviews = Review::whereIn('id', $request->review_ids);

        switch ($request->action) {
            case 'approve':
                $reviews->update(['is_approved' => true]);
                $message = 'Các đánh giá đã được duyệt thành công!';
                break;
            
            case 'reject':
                $reviews->update(['is_approved' => false]);
                $message = 'Các đánh giá đã bị từ chối!';
                break;
            
            case 'delete':
                $reviews->delete();
                $message = 'Các đánh giá đã được xóa thành công!';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Get review statistics
     */
    private function getReviewStats()
    {
        return [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending' => Review::where('is_approved', false)->count(),
            'average_rating' => Review::where('is_approved', true)->avg('rating'),
            'rating_distribution' => [
                5 => Review::where('rating', 5)->where('is_approved', true)->count(),
                4 => Review::where('rating', 4)->where('is_approved', true)->count(),
                3 => Review::where('rating', 3)->where('is_approved', true)->count(),
                2 => Review::where('rating', 2)->where('is_approved', true)->count(),
                1 => Review::where('rating', 1)->where('is_approved', true)->count(),
            ]
        ];
    }
}
