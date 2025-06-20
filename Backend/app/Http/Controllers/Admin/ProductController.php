<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('attachments')->paginate(10);
        return view('admin.pages.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $attribute = Attribute::get();
        $attributevalue = AttributeValue::with('attribute')->get();
        // dd($attributes);
        return view('admin.pages.products.create', [
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attribute,
            'attributevalue' => $attributevalue,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        // dd($request->all());

        // Bỏ try-catch quanh validated() để Laravel tự xử lý lỗi validate
        $validated = $request->validated();
        Log::info('Bắt đầu xử lý tạo sản phẩm', ['validated_data' => $validated]);

        try {

            $slug = Str::slug($validated['name']);
            Log::info('Tạo slug từ tên sản phẩm', ['slug' => $slug]);

            $productImageUrl = null;
            $productImageId = null;

            // Tạo sản phẩm
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


            Log::info('Tạo sản phẩm thành công', ['product_id' => $product->id]);

            // Upload ảnh chính nếu có
            if ($request->hasFile('product_image')) {
                Log::info('Bắt đầu upload ảnh chính');
                $uploaded = Cloudinary::upload($request->file('product_image')->getRealPath(), [
                    'folder' => 'products',
                ]);
                $product->update([
                    'product_image' => $uploaded->getSecurePath(),
                    'public_product_image_id' => $uploaded->getPublicId()
                ]);
                Log::info('Upload ảnh chính thành công', [
                    'url' => $uploaded->getSecurePath(),
                    'public_id' => $uploaded->getPublicId()
                ]);
            }

            // Upload ảnh đính kèm nếu có
            if ($request->hasFile('attachments')) {
                Log::info('Bắt đầu upload các tệp đính kèm');
                foreach ($request->file('attachments') as $image) {
                    $uploaded = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'product_attachments',
                    ]);

                    $product->attachments()->create([
                        'attachment_image' => $uploaded->getSecurePath(),
                        'public_attachment_image_id' => $uploaded->getPublicId(),
                    ]);

                    Log::info('Upload tệp đính kèm thành công', [
                        'url' => $uploaded->getSecurePath(),
                        'public_id' => $uploaded->getPublicId()
                    ]);
                }
            }

            // Gán thuộc tính sản phẩm (nhiều giá trị)
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId => $valueIds) {
                    foreach ($valueIds as $valueId) {
                        DB::table('attribute_product')->insert([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId,
                        ]);
                    }
                }
            }

            // Gán thuộc tính sản phẩm (nhiều giá trị)
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId => $valueIds) {
                    foreach ($valueIds as $valueId) {
                        DB::table('attribute_product')->insert([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId,
                        ]);
                    }
                }
            }

            // Gán biến thể
            if ($request->filled('variants_json')) {
                $variants = json_decode($request->variants_json, true);
                foreach ($variants as $index => $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $request->input("variants.$index.sku"),
                        'price' => $request->input("variants.$index.price"),
                        'quantity' => $request->input("variants.$index.quantity"),
                        'weight' => $request->input("variants.$index.weight"),
                        'active' => 1,
                    ]);

                    // Ảnh riêng cho từng biến thể
                    if ($request->hasFile("variants.$index.variant_image")) {
                        $file = $request->file("variants.$index.variant_image");
                        $upload = Cloudinary::upload($file->getRealPath(), ['folder' => 'variants']);
                        $variant->update([
                            'variant_image' => $upload->getSecurePath(),
                            'public_variant_image_id' => $upload->getPublicId()
                        ]);
                    }

                    // Gán thuộc tính cho biến thể
                    $attrValueIds = explode(',', $variantData['ids']);
                    foreach ($attrValueIds as $valueId) {
                        DB::table('attribute_value_variant')->insert([
                            'variant_id' => $variant->id,
                            'attribute_value_id' => $valueId,
                            'attribute_id' => \App\Models\AttributeValue::find($valueId)?->attribute_id ?? null,
                        ]);
                    }
                }
            }


            Log::info('Hoàn tất tạo sản phẩm và đính kèm', ['product_id' => $product->id]);
            return redirect()->route('products.index')->with('success', 'Tạo sản phẩm thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo sản phẩm', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.pages.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        // ❗️Validation nằm ngoài try-catch để Laravel tự redirect khi có lỗi
        $validated = $request->validated();

        try {
            $product = Product::findOrFail($id);

            // Tạo slug duy nhất
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            // Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'weight' => $validated['weight'] ?? null,
                'quantity' => $validated['quantity'],
                'quantity_warning' => $validated['quantity_warning'] ?? 0,
                'tags' => $validated['tags'] ?? null,
                'sku' => $validated['sku'],
                'active' => $request->input('active', 0),
            ]);

            // Nếu có ảnh đại diện mới
            if ($request->hasFile('product_image')) {
                if ($product->public_product_image_id) {
                    Cloudinary::destroy($product->public_product_image_id);
                }

                $uploaded = Cloudinary::upload($request->file('product_image')->getRealPath());
                $product->update([
                    'product_image' => $uploaded->getSecurePath(),
                    'public_product_image_id' => $uploaded->getPublicId(),
                ]);
            }

            // Xoá ảnh đính kèm cũ (nếu được đánh dấu)
            $removedAttachmentIds = explode(',', $request->input('removed_attachments', ''));
            foreach ($removedAttachmentIds as $attachmentId) {
                if (!$attachmentId) continue;
                $attachment = ProductAttachment::find($attachmentId);
                if ($attachment) {
                    Cloudinary::destroy($attachment->public_attachment_image_id);
                    $attachment->delete();
                }
            }

            // Thêm ảnh đính kèm mới
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $uploaded = Cloudinary::upload($image->getRealPath());
                    $product->attachments()->create([
                        'attachment_image' => $uploaded->getSecurePath(),
                        'public_attachment_image_id' => $uploaded->getPublicId(),
                    ]);
                }
            }

            return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        Log::info('Bắt đầu xoá sản phẩm ID: ' . $id);

        $product = Product::with('attachments')->findOrFail($id);

        if ($product->public_product_image_id) {
            try {
                Cloudinary::destroy($product->public_product_image_id);
            } catch (\Exception $e) {
                Log::warning("Không thể xoá ảnh chính: " . $e->getMessage());
            }
        }

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
