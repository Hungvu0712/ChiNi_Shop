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
        $attributes = Attribute::get();
        $attributeNames = Attribute::pluck('name', 'id');
        $categories = Category::get();
        $attributeValues = [];
        foreach ($attributes as $attribute) {
            $attributeValues[$attribute->id] = AttributeValue::where('attribute_id', $attribute->id)
                ->get()
                ->pluck('value', 'id')
                ->toArray();
        }
        return view('admin.pages.products.create', [
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes,
            'attributeValues'=>$attributeValues,
            'attributeNames'=>$attributeNames,
        ]);
    }

    public function store(StoreProductRequest $request)
{
    $validated = $request->validated();
    DB::beginTransaction();

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

        // Ảnh chính sản phẩm
        if ($request->hasFile('product_image')) {
            $upload = Cloudinary::upload($request->file('product_image')->getRealPath(), ['folder' => 'products']);
            $product->update([
                'product_image' => $upload->getSecurePath(),
                'public_product_image_id' => $upload->getPublicId(),
            ]);
        }

        // Ảnh đính kèm
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $img) {
                $upload = Cloudinary::upload($img->getRealPath(), ['folder' => 'product_attachments']);
                $product->attachments()->create([
                    'attachment_image' => $upload->getSecurePath(),
                    'public_attachment_image_id' => $upload->getPublicId(),
                ]);
            }
        }

        // Gắn attribute values (màu/size) vào sản phẩm
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeId => $valueIds) {
                $product->attributeValues()->attach($valueIds);
            }
        }

        // Lưu biến thể
        $variantsForJson = [];
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                $variantKey = $request->variant_keys[$index] ?? null;

                // Upload ảnh nếu có
                $variantImageUrl = null;
                $publicId = null;
                if (isset($variantData['variant_image']) && $variantData['variant_image']) {
                    $upload = Cloudinary::upload($variantData['variant_image']->getRealPath(), ['folder' => 'products/variants']);
                    $variantImageUrl = $upload->getSecurePath();
                    $publicId = $upload->getPublicId();
                }

                $variant = Variant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'],
                    'weight' => $variantData['weight'] ?? null,
                    'variant_image' => $variantImageUrl,
                    'public_variant_image_id' => $publicId,
                    'variant_key' => $variantKey,
                    'active' => $request->has('active') ? 1 : 0,
                ]);

                // Gán các attribute_value theo label (Đỏ/S)
                if ($variantKey) {
                    $labels = explode('/', $variantKey);
                    foreach ($labels as $label) {
                        $attrValue = AttributeValue::where('value', trim($label))->first();
                        if ($attrValue) {
                            VariantAttributeValue::create([
                                'variant_id' => $variant->id,
                                'attribute_value_id' => $attrValue->id,
                                'attribute_id' => $attrValue->attribute_id,
                            ]);
                        }
                    }
                }

                $variantsForJson[] = [
                    'id' => $variant->id,
                    'color' => explode('/', $variantKey)[0] ?? '',
                    'size' => explode('/', $variantKey)[1] ?? '',
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'quantity' => $variant->quantity,
                    'weight' => $variant->weight,
                    'variant_image' => $variant->variant_image,
                ];
            }
        }

        // Lưu lại JSON biến thể vào DB
        $product->update([
            'variants_json' => json_encode($variantsForJson, JSON_UNESCAPED_UNICODE)
        ]);

        DB::commit();
        return redirect()->route('products.index')->with('success', 'Tạo sản phẩm thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Lỗi khi tạo sản phẩm', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
    }
}


    public function show(string $id)
    {
        //
    }

   public function edit(string $id)
{
    $product = Product::with([
        'attachments',
        'variants.attributeValues.attribute',
    ])->findOrFail($id);

    $categories = Category::all();
    $brands = Brand::all();
    $attributes = Attribute::with('attributeValues')->get();

    $colorAttribute = Attribute::where('name', 'Màu sắc')->first();
    $sizeAttribute = Attribute::where('name', 'Size')->first();

    $colors = $colorAttribute ? $colorAttribute->attributeValues : collect();
    $sizes = $sizeAttribute ? $sizeAttribute->attributeValues : collect();

    // ✅ Tạo danh sách biến thể dạng color/size
    $productVariants = $product->variants->map(function ($variant) use ($colorAttribute, $sizeAttribute) {
        $colorValue = $variant->attributeValues->firstWhere('attribute_id', $colorAttribute->id ?? null);
        $sizeValue = $variant->attributeValues->firstWhere('attribute_id', $sizeAttribute->id ?? null);

        return [
            'id' => $variant->id,
            'color' => optional($colorValue)->value,
            'size' => optional($sizeValue)->value,
            'sku' => $variant->sku,
            'price' => $variant->price,
            'quantity' => $variant->quantity,
            'weight' => $variant->weight,
            'variant_image' => $variant->variant_image,
        ];
    })->filter(fn($v) => $v['color'] && $v['size'])->values();

    // ✅ Gán các attribute value đang chọn để đánh dấu lại checkbox
    $selectedValueIds = $product->variants->flatMap(function ($variant) {
        return $variant->attributeValues->pluck('id');
    })->unique()->toArray();

    return view('admin.pages.products.edit', compact(
        'product',
        'categories',
        'brands',
        'attributes',
        'colors',
        'sizes',
        'selectedValueIds',
        'productVariants'
    ));
}

public function update(UpdateProductRequest $request, $id)
{
    $product = Product::with('variants')->findOrFail($id);

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

    // Cập nhật ảnh đại diện
    if ($request->hasFile('product_image')) {
        $imageUrl = Cloudinary::upload($request->file('product_image')->getRealPath())->getSecurePath();
        $product->update(['product_image' => $imageUrl]);
    }

    // Xóa ảnh đính kèm cũ
    if ($request->filled('removed_attachments')) {
        $ids = explode(',', $request->removed_attachments);
        ProductAttachment::whereIn('id', $ids)->delete();
    }

    // Thêm ảnh đính kèm mới
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $img) {
            $imageUrl = Cloudinary::upload($img->getRealPath())->getSecurePath();
            ProductAttachment::create([
                'product_id' => $product->id,
                'attachment_image' => $imageUrl,
            ]);
        }
    }

    // Xóa các biến thể không còn
    $existingIds = $product->variants->pluck('id')->toArray();
    $requestIds = $request->input('variant_ids', []);
    $toDelete = array_diff($existingIds, $requestIds);
    Variant::whereIn('id', $toDelete)->delete();

    // Cập nhật hoặc thêm mới biến thể
    if ($request->has('variants')) {
        foreach ($request->variants as $index => $data) {
            $variantId = $data['id'] ?? null;
            $variantKey = $request->variant_keys[$index] ?? null;

            $variant = Variant::updateOrCreate(
                ['id' => $variantId],
                [
                    'product_id' => $product->id,
                    'sku' => $data['sku'],
                    'price' => $data['price'],
                    'quantity' => $data['quantity'],
                    'weight' => $data['weight'],
                    'variant_key' => $variantKey,
                ]
            );

            if (isset($data['variant_image']) && $data['variant_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = Cloudinary::upload($data['variant_image']->getRealPath())->getSecurePath();
                $variant->update(['variant_image' => $imagePath]);
            }

            // Gán lại attribute values
            if ($variantKey) {
                $variant->attributeValues()->detach();

                $parts = explode('/', $variantKey);
                foreach ($parts as $part) {
                    $attrValue = AttributeValue::where('value', trim($part))->first();
                    if ($attrValue) {
                        VariantAttributeValue::updateOrCreate([
                            'variant_id' => $variant->id,
                            'attribute_value_id' => $attrValue->id,
                            'attribute_id' => $attrValue->attribute_id,
                        ]);
                    }
                }
            }
        }
    }

    // Cập nhật lại biến thể json cho client
    $product->refresh()->load(['variants.attributeValues.attribute']);
    $product->variants_json = json_encode(
        $product->variants->map(function ($v) {
            return [
                'id' => $v->id,
                'color' => $v->attributeValues->firstWhere('attribute.name', 'Màu sắc')?->value,
                'size' => $v->attributeValues->firstWhere('attribute.name', 'Size')?->value,
                'sku' => $v->sku,
                'price' => $v->price,
                'quantity' => $v->quantity,
                'weight' => $v->weight,
                'variant_image' => $v->variant_image,
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
