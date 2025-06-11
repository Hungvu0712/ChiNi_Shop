<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::withCount('attachments')->paginate(10); // lấy kèm số lượng ảnh đính kèm
        return view('admin.pages.products.index', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.products.create');
    }


    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:products',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'product_image' => 'nullable|image',
            'weight' => 'nullable|string',
            'quantity' => 'required|integer',
            'quantity_warning' => 'nullable|integer',
            'tags' => 'nullable|string',
            'sku' => 'nullable|string',
            'active' => 'nullable|boolean',
            'attachments.*' => 'nullable|image',
        ]);

        // ✅ Tạo slug tự động từ tên sản phẩm
        $slug = Str::slug($validated['name']);

        $productImageUrl = null;
        $productImageId = null;

        // ✅ Upload ảnh chính nếu có
        if ($request->hasFile('product_image')) {
            $uploaded = Cloudinary::upload($request->file('product_image')->getRealPath(), [
                'folder' => 'products',
            ]);

            $productImageUrl = $uploaded->getSecurePath();
            $productImageId = $uploaded->getPublicId();
        }

        // ✅ Tạo bản ghi sản phẩm
        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'product_image' => $productImageUrl,
            'public_product_image_id' => $productImageId,
            'weight' => $validated['weight'] ?? null,
            'quantity' => $validated['quantity'],
            'quantity_warning' => $validated['quantity_warning'] ?? 0,
            'tags' => $validated['tags'] ?? null,
            'sku' => $validated['sku'] ?? null,
            'active' => $request->boolean('active', true),
        ]);

        // ✅ Upload các ảnh đính kèm nếu có
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $image) {
                $uploaded = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'product_attachments',
                ]);

                $product->attachments()->create([
                    'attachment_image' => $uploaded->getSecurePath(),
                    'public_attachment_image_id' => $uploaded->getPublicId(),
                ]);
            }
        }

        return redirect()->route('admin.pages.products.index')->with('success', 'Tạo sản phẩm thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
