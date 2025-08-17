<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'review' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $hasPurchased = OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('order_status', 'Hoàn thành')
                    ->where('payment_status', 'Đã thanh toán');
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Bạn cần mua và thanh toán sản phẩm này để bình luận.');
        }

        $alreadyReviewed = ProductReview::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Bạn đã bình luận sản phẩm này rồi.');
        }

        ProductReview::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'review' => $request->review, 
            'rating' => $request->rating ?? 5,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

}
