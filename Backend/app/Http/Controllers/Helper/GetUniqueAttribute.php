<?php

namespace App\Http\Controllers\Helper;

trait GetUniqueAttribute
{
    // Hàm để lấy các thuộc tính độc nhất
    public static function getUniqueAttributes($variants)
    {
        $uniqueAttributes = [];

        foreach ($variants as $variant) {
            foreach ($variant['attributes'] as $attribute) {
                $attrName = $attribute['name'];
                $attrValue = $attribute['pivot']['value'];
                $attributeId = $attribute['pivot']['attribute_id'];
                if (!isset($uniqueAttributes[$attrName])) {
                    $uniqueAttributes[$attrName] = [];
                }
                if (!in_array($attrValue, $uniqueAttributes[$attrName])) {
                    $uniqueAttributes[$attrName][$attribute['pivot']['attribute_value_id']] =  $attrValue;
                }
                // $uniqueAttributes[$attrName][$attributeId] = $attrName;
            }
        }
        return $uniqueAttributes;
    }
    public static function findVariantByAttributes($variants, $selectedAttributeItemIds)
    {
        foreach ($variants as $variant) {
            $match = true;
            foreach ($variant['attributes'] as $attribute) {

                $attrName = $attribute['name'];
                $attrItemId = $attribute['pivot']['attribute_item_id'];
               
                // Kiểm tra xem attribute_item_id đã chọn có khớp với biến thể không
                if (!isset($selectedAttributeItemIds[$attrName]) || $selectedAttributeItemIds[$attrName] != $attrItemId) {
                    $match = false;
                    break;
                }
            }
    
            if ($match) {
               

                return $variant;
            }
        }
    
        return null; // Không tìm thấy variant phù hợp
    }
}
