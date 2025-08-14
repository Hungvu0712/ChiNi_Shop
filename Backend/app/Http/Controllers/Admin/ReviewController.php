<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;

class ReviewController extends Controller
{
    public function __contruct(){
        $this->middleware('permission.404:review')->only('index', 'destroy');
    }
    public function index()
    {
        $reviews = ProductReview::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.review.index', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Xóa bình luận thành công!');
    }
}
