<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductWithVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Lấy các attribute và attribute_value từ DB
            $sizeAttr = Attribute::where('name', 'Size')->first();
            $colorAttr = Attribute::where('name', 'Color')->first();
            $materialAttr = Attribute::where('name', 'Chất liệu')->first();

            if (!$sizeAttr || !$colorAttr) {
                throw new \Exception('Không tìm thấy attribute Size hoặc Color');
            }

            $sizeValues = AttributeValue::where('attribute_id', $sizeAttr->id)->get(); // S, M
            $colorValues = AttributeValue::where('attribute_id', $colorAttr->id)->get(); // Red, Blue

            $category = Category::first();
            $brand = Brand::first();

            if (!$category || !$brand) {
                throw new \Exception('Chưa có Category hoặc Brand');
            }

            for ($i = 1; $i <= 5; $i++) {
                $name = "Sản phẩm $i";

                $product = Product::create([
                    'name' => $name,
                    'slug' => \Str::slug($name),
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'price' => 300000,
                    'description' => 'Mô tả sản phẩm mẫu',
                    'weight' => 0.5,
                    'quantity' => 50,
                    'quantity_warning' => 10,
                    'tags' => 'test, demo',
                    'sku' => 'SKU-' . strtoupper(\Str::random(5)),
                    'active' => 1,
                    'product_image' => 'https://picsum.photos/200/300?random=' . rand(1, 1000),
                    'public_product_image_id' => null,
                ]);

                // Tạo 2 biến thể mỗi sản phẩm
                $variantDataList = [
                    [
                        'size' => $sizeValues->first(),       // S
                        'color' => $colorValues->first(),     // Red
                    ],
                    [
                        'size' => $sizeValues->last(),        // M
                        'color' => $colorValues->last(),      // Blue
                    ],
                ];

                foreach ($variantDataList as $variantData) {
                    $variant = Variant::create([
                        'product_id' => $product->id,
                        'sku' => strtoupper('SP' . $i . '-' . $variantData['size']->value . '-' . $variantData['color']->value),
                        'price' => 300000,
                        'quantity' => 10,
                        'weight' => 0.5,
                        'variant_image' => 'https://picsum.photos/200/300?random=' . rand(1000, 9999),
                        'public_variant_image_id' => null,
                    ]);

                    // Gắn thuộc tính Size
                    $variant->attributes()->attach($sizeAttr->id, [
                        'attribute_value_id' => $variantData['size']->id,
                        'value' => $variantData['size']->value,
                    ]);

                    // Gắn thuộc tính Color
                    $variant->attributes()->attach($colorAttr->id, [
                        'attribute_value_id' => $variantData['color']->id,
                        'value' => $variantData['color']->value,
                    ]);
                }
            }

            DB::commit();
            echo "✅ Đã thêm 5 sản phẩm và các biến thể thành công.\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Lỗi khi seeding: " . $e->getMessage() . "\n";
        }
    }
}
