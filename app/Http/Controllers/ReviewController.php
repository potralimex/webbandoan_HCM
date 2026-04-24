<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Order;

class ReviewController extends Controller
{
    // Customer: submit review for a delivered order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:500',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min'      => 'Đánh giá tối thiểu 1 sao.',
            'rating.max'      => 'Đánh giá tối đa 5 sao.',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        if ($order->user_id !== Auth::id()) abort(403);
        if ($order->status !== 'delivered') {
            return back()->withErrors(['order' => 'Chỉ có thể đánh giá đơn hàng đã giao.']);
        }
        if ($order->review) {
            return back()->withErrors(['review' => 'Bạn đã đánh giá đơn hàng này rồi.']);
        }

        $review = Review::create([
            'user_id'       => Auth::id(),
            'restaurant_id' => $validated['restaurant_id'],
            'order_id'      => $validated['order_id'],
            'rating'        => $validated['rating'],
            'comment'       => $validated['comment'],
            'is_approved'   => false,
        ]);

        // Update restaurant rating
        $review->restaurant->updateRating();

        return back()->with('success', 'Đánh giá đã được gửi và đang chờ duyệt!');
    }

    // Admin: list reviews
    public function adminIndex(Request $request)
    {
        $this->authorize('admin-action');

        $query = Review::with(['user', 'restaurant']);

        if ($request->get('status') === 'pending') {
            $query->where('is_approved', false);
        } elseif ($request->get('status') === 'approved') {
            $query->where('is_approved', true);
        }

        $reviews = $query->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    // Admin: approve review
    public function approve(Review $review)
    {
        $this->authorize('admin-action');
        $review->update(['is_approved' => true]);
        $review->restaurant->updateRating();
        return back()->with('success', 'Đã duyệt đánh giá!');
    }

    // Admin: delete review
    public function destroy(Review $review)
    {
        $this->authorize('admin-action');
        $review->delete();
        if ($review->restaurant) $review->restaurant->updateRating();
        return back()->with('success', 'Đã xóa đánh giá!');
    }
}
