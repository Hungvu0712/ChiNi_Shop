<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log; // nhớ thêm ở đầu file


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
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.pages.products.create', [
            'categories' => $categories,
            'brands' => $brands
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        Log::info('Bắt đầu store()'); // DEBUG 1

        try {
            Log::info('Đang validate dữ liệu');
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
                'active' => 'nullable|in:true,false,1,0,on,off',
                'attachments.*' => 'nullable|image',
            ]);
            Log::info('Validate thành công', $validated);

            $slug = Str::slug($validated['name']);
            Log::info("Slug tạo ra: $slug");

            $productImageUrl = null;
            $productImageId = null;

            // Tạo bản ghi trước
            Log::info('Đang tạo bản ghi Product');
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
            Log::info('Tạo product thành công, ID: ' . $product->id);

            // Upload ảnh chính
            if ($request->hasFile('product_image')) {
                Log::info('Đang upload ảnh chính');
                try {
                    $uploaded = Cloudinary::upload($request->file('product_image')->getRealPath(), [
                        'folder' => 'products',
                    ]);

                    $productImageUrl = $uploaded->getSecurePath();
                    $productImageId = $uploaded->getPublicId();

                    $product->update([
                        'product_image' => $productImageUrl,
                        'public_product_image_id' => $productImageId
                    ]);
                    Log::info('Upload ảnh chính thành công');
                } catch (\Exception $e) {
                    Log::error('Lỗi khi upload ảnh chính: ' . $e->getMessage());
                }
            }

            // Upload ảnh đính kèm
            if ($request->hasFile('attachments')) {
                Log::info('Đang upload ảnh đính kèm');
                foreach ($request->file('attachments') as $image) {
                    try {
                        $uploaded = Cloudinary::upload($image->getRealPath(), [
                            'folder' => 'product_attachments',
                        ]);

                        $product->attachments()->create([
                            'attachment_image' => $uploaded->getSecurePath(),
                            'public_attachment_image_id' => $uploaded->getPublicId(),
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Lỗi upload ảnh đính kèm: ' . $e->getMessage());
                    }
                }
            }

            Log::info('Kết thúc store() - thành công');

            return redirect()->route('products.index')->with('success', 'Tạo sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi trong store(): ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
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
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.pages.products.edit', compact('product', 'categories', 'brands'));
    }



    public function update(Request $request, $id)
    {
        Log::info('Bắt đầu update()');

        $product = Product::with('attachments')->findOrFail($id);

        $request->validate([
            'name' => 'required|unique:products,name,' . $product->id,
            'category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required|numeric',
            'weight' => 'nullable|string',
            'quantity' => 'required|integer',
            'quantity_warning' => 'required|integer',
            'sku' => 'required',
            'active' => 'boolean',
            'product_image' => 'nullable|image|max:2048',
            'attachments.*' => 'nullable|image|max:2048',

        ]);

        $product->fill($request->only([
            'name',
            'category_id',
            'brand_id',
            'price',
            'description',
            'weight',
            'quantity',
            'quantity_warning',
            'tags',
            'sku',
            'active'
        ]));

        $product->slug = Str::slug($product->name);

        // ✅ Xử lý ảnh chính nếu có
        if ($request->hasFile('product_image')) {
            if ($product->public_product_image_id) {
                try {
                    Cloudinary::destroy($product->public_product_image_id);
                } catch (\Exception $e) {
                    Log::warning("Không thể xoá ảnh chính cũ: " . $e->getMessage());
                }
            }

            $result = Cloudinary::upload($request->file('product_image')->getRealPath(), [
                'folder' => 'products'
            ]);

            $product->product_image = $result->getSecurePath();
            $product->public_product_image_id = $result->getPublicId();
        }

        $product->save();

        // ✅ Xoá toàn bộ ảnh đính kèm cũ → upload ảnh mới
        if ($request->hasFile('attachments')) {
            $publicIds = $product->attachments
                ->pluck('public_attachment_image_id')
                ->filter()
                ->toArray();

            if (!empty($publicIds)) {
                try {
                    Cloudinary::destroy($publicIds);
                } catch (\Exception $e) {
                    Log::warning("Không thể xoá ảnh đính kèm cũ: " . $e->getMessage());
                }
            }

            $product->attachments()->delete();

            foreach ($request->file('attachments') as $file) {
                $uploaded = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'product_attachments'
                ]);

                ProductAttachment::create([
                    'product_id' => $product->id,
                    'attachment_image' => $uploaded->getSecurePath(),
                    'public_attachment_image_id' => $uploaded->getPublicId(),
                ]);
            }
        }

        Log::info('Cập nhật sản phẩm thành công: ' . $product->id);

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function destroy($id)
    {
        Log::info('Bắt đầu xoá sản phẩm ID: ' . $id);

        $product = Product::with('attachments')->findOrFail($id);

        // Xoá ảnh chính nếu có
        if ($product->public_product_image_id) {
            try {
                Cloudinary::destroy($product->public_product_image_id);
            } catch (\Exception $e) {
                Log::warning("Không thể xoá ảnh chính: " . $e->getMessage());
            }
        }

        // Xoá toàn bộ ảnh đính kèm bằng batch
        $publicIds = $product->attachments
            ->pluck('public_attachment_image_id')
            ->filter()
            ->toArray();

        if (!empty($publicIds)) {
            try {
                Cloudinary::destroy($publicIds);
            } catch (\Exception $e) {
                Log::warning("Không thể xoá ảnh đính kèm: " . $e->getMessage());
            }
        }

        $product->attachments()->delete();
        $product->delete();

        Log::info('Đã xoá hoàn tất sản phẩm ID: ' . $id);

        return redirect()->route('products.index')->with('success', 'Đã xoá sản phẩm thành công!');
    }
}
