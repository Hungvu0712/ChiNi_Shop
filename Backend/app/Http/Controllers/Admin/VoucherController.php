<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vouchers\StoreRequest;
use App\Http\Requests\Vouchers\UpdateRequest;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission.404:voucher-list')->only('index', 'show');
        // $this->middleware('permission.404:voucher-create')->only('create', 'store');
        // $this->middleware('permission.404:voucher-edit')->only('edit', 'update');
        // $this->middleware('permission.404:voucher-delete')->only('destroy');
        $this->middleware('permission.404:crudvoucher')->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        return view('admin.pages.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.pages.vouchers.create");
    }

    public function store(StoreRequest $request)
    {
        $voucher = new Voucher();
        $voucher->code = $request->code;
        $voucher->title = $request->title;
        $voucher->voucher_type = 'discount'; // ✅ cố định discount
        $voucher->start_date = $request->start_date;
        $voucher->end_date = $request->end_date;
        $voucher->limit = $request->limit;
        $voucher->is_active = $request->has('is_active') ? 1 : 0;

        // Xử lý discount
        $voucher->discount_type = $request->discount_type;
        $voucher->value = $request->value;
        $voucher->min_order_value = $request->min_order_value ?? 0;
        $voucher->max_discount_value = $request->discount_type === 'percent'
            ? $request->max_discount_value
            : 0;

        $voucher->save();

        return redirect()->route('vouchers.index')->with('success', 'Tạo voucher thành công!');
    }



    public function edit(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.pages.vouchers.edit', compact('voucher'));
    }

    public function update(UpdateRequest $request, string $id)
    {
        $voucher = Voucher::findOrFail($id);

        // Lấy dữ liệu đã validate (nếu dùng FormRequest). Nếu không, fallback sang all().
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        // Chuẩn hoá kiểu giảm giá
        $discountType = $request->input('discount_type') === 'percent' ? 'percent' : 'amount';

        // Giá trị giảm
        $value = (float) $request->input('value', 0);

        // max_discount_value
        if ($discountType === 'percent') {
            $maxDiscount = (float) $request->input('max_discount_value', 0);

            // Nếu maxDiscount < value thì ép = value
            if ($maxDiscount < $value) {
                $maxDiscount = $value;
            }
        } else {
            // Với amount thì max_discount_value = 0
            $maxDiscount = 0;
        }

        // Gán các trường khác
        $voucher->code = $request->input('code', $voucher->code);
        if ($request->has('name')) $voucher->name = $request->input('name');
        if ($request->has('description')) $voucher->description = $request->input('description');

        if ($request->has('start_date')) $voucher->start_date = $request->input('start_date');
        if ($request->has('end_date')) $voucher->end_date = $request->input('end_date');

        if ($request->has('limit')) $voucher->limit = $request->input('limit');
        if ($request->has('min_order_value')) $voucher->min_order_value = (float) $request->input('min_order_value', 0);

        $voucher->is_active = $request->boolean('is_active');
        $voucher->discount_type = $discountType; // 'percent' | 'amount'
        $voucher->value = max(0, $value); // không âm
        $voucher->max_discount_value = max(0, $maxDiscount); // nếu amount → 0

        // Bảo vệ thêm cho percent: giới hạn 0–100
        if ($discountType === 'percent') {
            if ($voucher->value > 100) {
                $voucher->value = 100;
            }
        }

        $voucher->save();

        return redirect()->route('vouchers.index')
            ->with('success', 'Cập nhật voucher thành công');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);

        $voucher->delete();

        return redirect()
            ->route('vouchers.index')
            ->with('success', 'Xóa thành công');
    }
}
