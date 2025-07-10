<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Http\Controllers\Helper\GetUniqueAttribute;
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

            // Lấy danh sách các `attributeValues` hợp lệ từ request
            $validAttributeItems = collect($request->input("attributeValues"))
                ->flatMap(fn($items) => $items)
                ->toArray();
          
            // Duyệt qua từng product_variants
            foreach ($validated['product_variants'] as $variant) {
                // Kiểm tra tất cả `attribute_item_id` trong biến thể có hợp lệ không
                $isValidVariant = collect($variant["attribute_item_id"])
                    ->every(fn($item) => in_array($item['id'], $validAttributeItems));
                
                // Nếu biến thể không hợp lệ, bỏ qua
                if (!$isValidVariant) {
                    continue;
                }
                $variantImageUrl = null;
                $publicId = null;
                if (isset($variant['variant_image']) && $variant['variant_image']) {
                    $upload = Cloudinary::upload($variant['variant_image']->getRealPath(), ['folder' => 'products/variants']);
                    $variantImageUrl = $upload->getSecurePath();
                    $publicId = $upload->getPublicId();
                }
                // Thêm product_variant vào DB
                $productVariant = Variant::query()->create([
                    'product_id'=> $product->id,
                    'sku'=>$variant["sku"],
                    'price'=>$variant["price"],
                    'quantity'=>$variant["quantity"],
                    'weight'=>$variant['weight'] ?? null,
                    'variant_image'=>$variantImageUrl,
                    'public_variant_image_id'=>$publicId,
                ]);

                // Gắn attribute_item_id cho biến thể
                foreach ($variant["attribute_item_id"] as $value) {
                    $attribute_id = null;

                    foreach ($request->input('attributeId') as $attr_id) {
                        if (in_array($value['id'], $request->input('attributeValues')[$attr_id])) {
                            $attribute_id = $attr_id;
                            break;
                        }
                    }

                    if ($attribute_id !== null) {
                        $productVariant->attributes()->attach(
                            $attribute_id,
                            ["attribute_value_id" => $value["id"], "value" => $value['value']]
                        );
                    }
                }
            }

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
        try {
            $product = Product::query()->findOrFail($id)->load(['attachments', 'variants.attributes']);
            $uniqueAttributes = GetUniqueAttribute::getUniqueAttributes($product->variants->toArray());
            $categories = Category::query()->latest('id')->get();
            $attributes = Attribute::with(["attributeValues"])->get();
            $brands = Brand::query()->pluck('name', 'id');
            $selectedAttributeIds = []; // vd: [1, 2]
            $selectedAttributeValueIds = []; // vd: [1 => [3, 4], 2 => [5]]

            foreach ($attributes as $attribute) {
                $attrName = $attribute->name;
                $attrId = $attribute->id;

                if (isset($uniqueAttributes[$attrName])) {
                    $selectedAttributeIds[] = $attrId;
                    $selectedAttributeValueIds[$attrId] = array_keys($uniqueAttributes[$attrName]); // chỉ lấy id
                }
            }

            // Dùng để JS dễ xử lý
            $attributeNames = $attributes->pluck('name', 'id'); // [1 => 'Color', 2 => 'Size']

            $attributeValues = [];
            foreach ($attributes as $attribute) {
                $attributeValues[$attribute->id] = $attribute->attributeValues->pluck('value', 'id')->toArray();
            }

            return view('admin.pages.products.edit', compact(
                'product',
                'categories',
                'brands',
                'attributes',
                'attributeNames',
                'attributeValues',
                'selectedAttributeIds',
                'selectedAttributeValueIds'
            ));
        } catch (\Exception $e) {
            Log::error('Lỗi khi sửa sản phẩm', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {   
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

                // Lấy biến thể hiện tại của sản phẩm từ database
                // Tải các biến thể hiện có của sản phẩm và chuyển đổi thành mảng
                $existingVariants = $product->variants->toArray();
                // Tạo một mảng để lưu trữ ID của các biến thể được xử lý từ yêu cầu
                $processedVariantIds = [];
                $syncVariant = [];
                // Vòng lặp qua các biến thể từ yêu cầu
                foreach ($request->product_variants as $keys => $item) {
                    // Đặt lại $syncVariant cho mỗi biến thể
                    // $syncVariant = [];

                    // Xử lý hình ảnh
                    if (isset($item['variant_image']) && $item['variant_image'] instanceof \Illuminate\Http\UploadedFile) {
                        $upload = Cloudinary::upload($item['variant_image']->getRealPath());
                        $imageVariant = $upload->getSecurePath();
                        $imageId = $upload->getPublicId();
                    }
                    // Kiểm tra xem biến thể có tồn tại trong DB không, nếu có thì update, nếu không thì tạo mới
                    if (isset($existingVariants[$keys])) {
                  
                        $productVariant = Variant::findOrFail($existingVariants[$keys]["id"]);
                        // Cập nhật biến thể hiện có
                        Variant::where('id', $existingVariants[$keys]["id"])
                            ->update([
                                "product_id" => $product->id,
                                "price" => $item["price"],
                                "sku" => $item["sku"],
                                "quantity" => $item["quantity"],
                                "variant_image" => isset($item['variant_image']) ? ($imageVariant ?? $productVariant->variant_image) : $productVariant->variant_image,
                                "public_variant_image_id"=>isset($item['variant_image']) ? ($imageId ?? $productVariant->public_variant_image_id) : $productVariant->public_variant_image_id,
                                "weight" => $item["weight"],
                            ]);

                        // Thêm ID vào mảng đã xử lý
                        $processedVariantIds[] = $existingVariants[$keys]["id"];
                    } else {
                        $productVariant = Variant::create([
                            "product_id" => $product->id,
                            "price" => $item["price"],
                            "sku" => $item["sku"],
                            "quantity" => $item["quantity"],
                            "variant_image" => $imageVariant?? null,
                            "public_variant_image_id"=>$imageId ?? null,
                            "weight" => $item["weight"],
                        ]);

                        // Thêm ID vào mảng đã xử lý
                        $processedVariantIds[] = $productVariant->id;
                    }

                    foreach ($item["attribute_item_id"] as  $value) {

                        $attribute_id = null;

                        foreach ($request->input('attributeId') as  $attr_id) { //2,1

                            if (in_array($value['id'], $request->input('attributeValues')[$attr_id])) {
                                $attribute_id = $attr_id;
                                break;
                            }
                        }
                      
                        if ($attribute_id !== null) {

                            $syncVariant[$attribute_id] = [
                                "attribute_value_id" => $value["id"],
                                "value" => $value["value"]
                            ];
                        }
                    }
            
                    // Đồng bộ hóa thuộc tính
                    $productVariant->attributes()->sync($syncVariant);
                }

                // Sau khi xử lý tất cả các biến thể từ yêu cầu, xóa các biến thể không được xử lý
                if (!empty($existingVariants)) {
                    
                    // Lấy tất cả ID của biến thể hiện có
                    $existingVariantIds = array_column($existingVariants, 'id');
                    // dd($existingVariantIds,$processedVariantIds);

                    // Xác định các ID cần xóa
                    $variantIdsToDelete = array_diff($existingVariantIds, $processedVariantIds);
                    
                    if (!empty($variantIdsToDelete)) {
                        // Tách các thuộc tính liên kết trước khi xóa
                        Variant::whereIn('id', $variantIdsToDelete)->each(function ($variant) {
                            $variant->attributes()->detach(); // Tách các thuộc tính
                            $variant->delete(); // Xóa biến thể
                        });
                    }
                }
            });
            return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi update sản phẩm', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
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
