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
                'active' => $request->has('active') ? 1 : 0,
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
        Log::info("Bắt đầu cập nhật sản phẩm ID: $id");

        $product = Product::findOrFail($id);
        Log::info("Tìm thấy sản phẩm: {$product->name}");

        // Validate dữ liệu đầu vào
        Log::info("Bắt đầu validate dữ liệu");
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'weight' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'quantity_warning' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);
        Log::info("Validate thành công", $validated);

        // Sinh slug duy nhất từ name
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        Log::info("Slug sinh ra: $slug");

        // Cập nhật các trường cơ bản
        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'quantity_warning' => $request->quantity_warning,
            'tags' => $request->tags,
            'sku' => $request->sku,
            'active' => $request->has('active') ? 1 : 0,
        ]);
        Log::info("Đã cập nhật thông tin cơ bản cho sản phẩm ID: $id");

        // Xử lý ảnh đại diện nếu người dùng chọn ảnh mới
        if ($request->hasFile('product_image')) {
            Log::info("Người dùng chọn ảnh đại diện mới");
            if ($product->public_product_image_id) {
                Cloudinary::destroy($product->public_product_image_id);
                Log::info("Đã xoá ảnh đại diện cũ trên Cloudinary: {$product->public_product_image_id}");
            }

            $uploaded = Cloudinary::upload($request->file('product_image')->getRealPath());
            $product->update([
                'product_image' => $uploaded->getSecurePath(),
                'public_product_image_id' => $uploaded->getPublicId(),
            ]);
            Log::info("Đã upload ảnh đại diện mới: " . $uploaded->getSecurePath());
        }

        // Xử lý ảnh đính kèm bị người dùng loại bỏ khỏi form
        $removedAttachmentIds = explode(',', $request->input('removed_attachments', ''));
        foreach ($removedAttachmentIds as $id) {
            if (!$id) continue;
            $attachment = ProductAttachment::find($id);
            if ($attachment) {
                Cloudinary::destroy($attachment->public_attachment_image_id);
                $attachment->delete();
                Log::info("Đã xoá ảnh đính kèm ID: $id - public_id: {$attachment->public_attachment_image_id}");
            }
        }

        // Xử lý ảnh đính kèm mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploaded = Cloudinary::upload($image->getRealPath());

                ProductAttachment::create([
                    'product_id' => $product->id,
                    'attachment_image' => $uploaded->getSecurePath(),
                    'public_attachment_image_id' => $uploaded->getPublicId(),
                ]);
                Log::info("Đã thêm ảnh đính kèm mới: " . $uploaded->getSecurePath());
            }
        }

        Log::info("Hoàn tất cập nhật sản phẩm ID: $id");

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
