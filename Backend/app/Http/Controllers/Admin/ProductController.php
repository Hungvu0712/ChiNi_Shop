<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttachment;
use App\Models\VariantAttributeValue;
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
        $validated = $request->validated();
        Log::info('Bắt đầu xử lý tạo sản phẩm', ['validated_data' => $validated]);

        try {
            $slug = Str::slug($validated['name']);
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'quantity' => $validated['quantity'],
                'quantity_warning' => $validated['quantity_warning'] ?? 0,
                'tags' => $validated['tags'] ?? null,
                'sku' => $validated['sku'] ?? null,
                'active' => $request->has('active') ? 1 : 0,
            ]);

            // Upload ảnh chính
            if ($request->hasFile('product_image')) {
                $uploaded = Cloudinary::upload($request->file('product_image')->getRealPath(), ['folder' => 'products']);
                $product->update([
                    'product_image' => $uploaded->getSecurePath(),
                    'public_product_image_id' => $uploaded->getPublicId()
                ]);
            }

            // Upload ảnh đính kèm
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $image) {
                    $uploaded = Cloudinary::upload($image->getRealPath(), ['folder' => 'product_attachments']);
                    $product->attachments()->create([
                        'attachment_image' => $uploaded->getSecurePath(),
                        'public_attachment_image_id' => $uploaded->getPublicId(),
                    ]);
                }
            }

            // Gắn attribute_values (thuộc tính) vào sản phẩm
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attrId => $valueIds) {
                    foreach ($valueIds as $valueId) {
                        $product->attributeValues()->attach($valueId);
                    }
                }
            }

            // Xử lý lưu biến thể sản phẩm
            if ($request->has('variants')) {
                foreach ($request->variants as $index => $variantData) {
                    $variantImageUrl = null;
                    if (isset($variantData['variant_image']) && $variantData['variant_image']) {
                        $uploaded = Cloudinary::upload($variantData['variant_image']->getRealPath(), [
                            'folder' => 'products/variants'
                        ]);
                        $variantImageUrl = $uploaded->getSecurePath();
                    }

                    $variant = Variant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                        'weight' => $variantData['weight'] ?? null,
                        'variant_image' => $variantImageUrl,
                        'public_variant_image_id' => $variantImageUrl ? $uploaded->getPublicId() : null,
                        'active' => $request->has('active') ? 1 : 0
                    ]);

                    // Lưu các attribute_values cho variant
                    if (isset($request->variant_keys[$index])) {
                        $valueIds = explode(',', $request->variant_keys[$index]);

                        foreach ($valueIds as $valueId) {
                            // Lấy thông tin AttributeValue để lấy ra attribute_id
                            $attributeValue = AttributeValue::find($valueId);
                            if ($attributeValue) {
                                VariantAttributeValue::create([
                                    'variant_id' => $variant->id,
                                    'attribute_value_id' => $attributeValue->id,
                                    'attribute_id' => $attributeValue->attribute_id,
                                ]);
                            }
                        }
                    }
                }
            }

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
        $product = Product::with(['attachments', 'variants.attributeValues'])->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('attributeValues')->get();

        // Biến thể hiện tại -> JSON để đẩy vào hidden input
$product->variants_json = $product->variants->map(function ($variant) {
    return [
        'id' => $variant->id,
        'variant_key' => $variant->attributeValues->pluck('id')->sort()->values()->implode(','),
        'sku' => $variant->sku,
        'price' => $variant->price,
        'quantity' => $variant->quantity,
        'weight' => $variant->weight,
        'variant_image' => $variant->variant_image,
    ];
})->values()->toJson();

        $selectedValueIds = $product->variants->flatMap(function ($variant) {
            return $variant->attributeValues->pluck('id');
        })->unique()->toArray();

        return view('admin.pages.products.edit', compact(
            'product',
            'categories',
            'brands',
            'attributes',
            'selectedValueIds'
        ));
    }

    public function update(UpdateProductRequest $request, $id)
{
    $product = Product::with('variants')->findOrFail($id);

    // 1. Cập nhật thông tin sản phẩm
    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,
        'description' => $request->description,
        'weight' => $request->weight,
        'quantity' => $request->quantity,
        'quantity_warning' => $request->quantity_warning,
        'tags' => $request->tags,
        'sku' => $request->sku,
        'active' => $request->active ?? 0,
    ]);

    // 2. Ảnh đại diện
    if ($request->hasFile('product_image')) {
        $imageUrl = Cloudinary::upload($request->file('product_image')->getRealPath())->getSecurePath();
        $product->update(['product_image' => $imageUrl]);
    }

    // 3. Xóa ảnh đính kèm
    if ($request->removed_attachments) {
        $ids = explode(',', $request->removed_attachments);
        ProductAttachment::whereIn('id', $ids)->delete();
    }

    // 4. Thêm ảnh đính kèm mới
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $img) {
            $imageUrl = Cloudinary::upload($img->getRealPath())->getSecurePath();
            ProductAttachment::create([
                'product_id' => $product->id,
                'attachment_image' => $imageUrl,
            ]);
        }
    }

    // 5. Cập nhật các biến thể cũ
    if ($request->has('variants') && $request->has('variant_keys')) {
        $existingVariantIds = $product->variants->pluck('id')->toArray();
        $requestVariantIds = $request->input('variant_ids', []);

        // Xóa biến thể không còn
        $toDelete = array_diff($existingVariantIds, $requestVariantIds);
        if (!empty($toDelete)) {
            Variant::whereIn('id', $toDelete)->delete();
        }

        foreach ($request->variants as $index => $variantData) {
            $variantId = $request->variant_ids[$index] ?? null;
            $variantKey = $request->variant_keys[$index] ?? null;

            $variant = Variant::updateOrCreate(
                ['id' => $variantId],
                [
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'],
                    'weight' => $variantData['weight'],
                    'variant_key' => $variantKey,
                ]
            );

            if (isset($variantData['variant_image']) && $variantData['variant_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = Cloudinary::upload($variantData['variant_image']->getRealPath())->getSecurePath();
                $variant->update(['variant_image' => $imagePath]);
            }

            if (!empty($variantKey)) {
                $variant->attributeValues()->detach();
                $valueIds = explode(',', $variantKey);
                foreach ($valueIds as $valueId) {
                    $attrId = AttributeValue::find($valueId)?->attribute_id;
                    if ($attrId) {
                        VariantAttributeValue::create([
                            'variant_id' => $variant->id,
                            'attribute_value_id' => $valueId,
                            'attribute_id' => $attrId,
                        ]);
                    }
                }
            }
        }
    }

    // 6. Tạo biến thể mới
    if ($request->has('variants_new')) {
        $offset = count($request->variants ?? []);
        foreach ($request->variants_new as $index => $variantData) {
            $variantKey = $request->variant_keys[$offset + $index] ?? null;

            $variant = Variant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'quantity' => $variantData['quantity'],
                'weight' => $variantData['weight'],
                'variant_key' => $variantKey,
            ]);

            if (isset($variantData['variant_image']) && $variantData['variant_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = Cloudinary::upload($variantData['variant_image']->getRealPath())->getSecurePath();
                $variant->update(['variant_image' => $imagePath]);
            }

            if (!empty($variantKey)) {
                $valueIds = explode(',', $variantKey);
                foreach ($valueIds as $valueId) {
                    $attrId = AttributeValue::find($valueId)?->attribute_id;
                    if ($attrId) {
                        VariantAttributeValue::create([
                            'variant_id' => $variant->id,
                            'attribute_value_id' => $valueId,
                            'attribute_id' => $attrId,
                        ]);
                    }
                }
            }
        }
    }

    // 7. Cập nhật lại biến `variants_json` (dùng cho form edit)
    $product->variants_json = json_encode(
        $product->variants()->get(['id', 'variant_key', 'sku', 'price', 'quantity', 'weight'])->map(function ($v) {
            return [
                'id' => $v->id,
                'variant_key' => $v->variant_key,
                'sku' => $v->sku,
                'price' => $v->price,
                'quantity' => $v->quantity,
                'weight' => $v->weight,
            ];
        })
    );
    $product->save();

    return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
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
