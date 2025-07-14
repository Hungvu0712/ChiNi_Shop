<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'rating'     => 'required|integer|min:1|max:5',
        'review'     => 'required|string|max:1000',
        'comAttachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    // Lưu đánh giá
    $review = ProductReview::create([
        'product_id' => $request->product_id,
        'user_id'    => auth()->id(),
        'rating'     => $request->rating,
        'review'     => $request->review,
    ]);

    // Lưu hình ảnh đính kèm nếu có
    if ($request->hasFile('comAttachments')) {
        foreach ($request->file('comAttachments') as $image) {
            $uploaded = cloudinary()->upload($image->getRealPath())->getSecurePath();

            $review->images()->create([
                'image_url' => $uploaded,
            ]);
        }
    }

    return redirect()
        ->route('client.shop.show', Product::find($request->product_id)->slug)
        ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
}

}
