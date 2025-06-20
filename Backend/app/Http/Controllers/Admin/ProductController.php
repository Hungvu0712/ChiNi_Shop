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

    $selectedAttributeValueIds = $product->variants->flatMap(function ($variant) {
        return $variant->attributeValues->pluck('id');
    })->unique()->toArray();

    return view('admin.pages.products.edit', compact(
        'product', 'categories', 'brands', 'attributes', 'selectedAttributeValueIds'
    ));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required',
        'brand_id' => 'required',
        'sku' => 'required|unique:products,sku,' . $id,
    ]);

    $product = Product::with('variants')->findOrFail($id);

    // Cập nhật thông tin chính
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

    // Upload ảnh chính mới nếu có
    if ($request->hasFile('product_image')) {
        $imageUrl = Cloudinary::upload($request->file('product_image')->getRealPath())->getSecurePath();
        $product->update(['product_image' => $imageUrl]);
    }

    // Xoá các ảnh đính kèm đã xoá
    if ($request->removed_attachments) {
        $ids = explode(',', $request->removed_attachments);
        ProductAttachment::whereIn('id', $ids)->delete();
    }

    // Thêm ảnh đính kèm mới
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $img) {
            $imageUrl = Cloudinary::upload($img->getRealPath())->getSecurePath();
            ProductAttachment::create([
                'product_id' => $product->id,
                'attachment_image' => $imageUrl,
            ]);
        }
    }

    // Xoá các biến thể cũ
    $product->variants()->delete();

    // Tạo lại các biến thể mới
    if ($request->variants && $request->variant_keys) {
        foreach ($request->variants as $index => $variantData) {
            $variant = Variant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'quantity' => $variantData['quantity'],
                'weight' => $variantData['weight'],
            ]);

            // Ảnh biến thể
            if (isset($variantData['variant_image'])) {
                $imagePath = Cloudinary::upload($variantData['variant_image']->getRealPath())->getSecurePath();
                $variant->update(['variant_image' => $imagePath]);
            }

            // Lưu các giá trị thuộc tính tương ứng với biến thể
            if (isset($request->variant_keys[$index])) {
                $valueIds = explode(',', $request->variant_keys[$index]);
                foreach ($valueIds as $valueId) {
                    $attrId = AttributeValue::find($valueId)?->attribute_id;
                    if ($attrId) {
                        VariantAttributeValue::create([
                            'variant_id' => $variant->id,
                            'attribute_value_id' => $valueId,
                            'attribute_id' => $attrId
                        ]);
                    }
                }
            }
        }
    }

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
