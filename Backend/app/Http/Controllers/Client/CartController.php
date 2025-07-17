<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\GetUniqueAttribute;
use App\Http\Requests\Cart\StoreCart;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          try {
            $user_id = Auth::id();
            $cart = Cart::query()->where('user_id', $user_id)->with([
                "cartitems",
                "cartitems.product",
                "cartitems.productvariant.attributes",
            ])->first();
            if (!$cart) {
               return view('client.pages.cart',['message'=>"Hiện tại bạn không có sản phẩm nào"]);
            } else {
                $cart->toArray();
            }
            $sub_total = 0;
            foreach ($cart["cartitems"] as  $key =>  $cartitem) {
                $quantity = $cartitem["quantity"];

                if ($cartitem["productvariant"]) {
                    $variant_price = $cartitem["productvariant"]["price"];

                    $cart["cartitems"][$key]["total_price"] = $variant_price * $quantity;
                }
                $sub_total += $cart["cartitems"][$key]["total_price"];
            }
            return view('client.pages.cart',compact('cart','sub_total'));
        }catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách cart', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCart $request)
    {
        try {
            $data = $request->validated();
           
        } catch (\Exception $e) {
            Log::error('Đã có lỗi xảy ra', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    "quantity" => "required|integer|min:1",
                    "product_variant" => "required|array" // Đảm bảo biến thể được gửi đúng định dạng
                ]);
                $cart_item = CartItem::query()->findOrFail($id);
               
                if ($cart_item->variant_id) {
                    // Nếu sản phẩm là biến thể
                    $product_variant = Product::query()
                        ->findOrFail($cart_item->product_id)
                        ->load(["variants", "variants.attributes"])
                        ->toArray();

                    $findVariant = GetUniqueAttribute::findVariantByAttributes(
                        $product_variant["variants"],
                        $request->input('product_variant')
                    );
                        if ($findVariant["quantity"] < $request->input('quantity')) {
                            return response()->json([
                                "message" => "Số lượng bạn cập nhật đã vượt quá số lượng sản phẩm tồn kho",
                            ], Response::HTTP_INTERNAL_SERVER_ERROR);
                        }

                        $cart_item->variant_id = $findVariant["id"];
                        $cart_item->quantity = $request->input("quantity");
                        $cart_item->save();
                }

                return response()->json(["message" => "Cập nhật giỏ hàng thành công."], Response::HTTP_OK);
            });
        }catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $cart_item_ids = json_decode($id, true); // đây là mảng từ URL
                if (!is_array($cart_item_ids)) {
                    throw new \Exception('Dữ liệu không hợp lệ.');
                }

                foreach ($cart_item_ids as $item_id) {
                    CartItem::query()->findOrFail($item_id)->delete();
                }

                // Lấy lại cart để kiểm tra còn gì không
                $cart = Cart::query()->with('cartitems')->first();
                if ($cart && $cart->cartitems->isEmpty()) {
                    $cart->delete();
                }

                return back()->with('message', 'Xoá dữ liệu thành công.');
            });
        } catch (\Exception $e) {
            Log::error('Lỗi khi xoá cart item', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }
}
