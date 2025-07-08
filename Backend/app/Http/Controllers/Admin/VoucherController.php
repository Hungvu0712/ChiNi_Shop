<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vouchers\StoreRequest;
use App\Http\Requests\Vouchers\UpdateRequest;
use App\Models\Voucher;

class VoucherController extends Controller
{
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code',
            'title' => 'required|string|max:255',
            'voucher_type' => 'required|in:discount,freeship',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'limit' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->voucher_type === 'discount') {
            $request->validate([
                'discount_type' => 'required|in:amount,percent',
                'value' => 'required|numeric|min:0',
                'min_order_value' => 'nullable|numeric|min:0',
                'max_discount_value' => 'nullable|numeric|min:0',
            ]);
        }

        $voucher = new Voucher();
        $voucher->code = $validated['code'];
        $voucher->title = $validated['title'];
        $voucher->voucher_type = $validated['voucher_type'];
        $voucher->start_date = $validated['start_date'];
        $voucher->end_date = $validated['end_date'];
        $voucher->limit = $validated['limit'];
        $voucher->is_active = $request->has('is_active') ? true : false;

        if ($voucher->voucher_type === 'discount') {
            $voucher->discount_type = $request->discount_type;
            $voucher->value = $request->value;
            $voucher->min_order_value = $request->min_order_value ?? 0;
            $voucher->max_discount_value = $request->max_discount_value ?? 0;
        } else {
            $voucher->discount_type = 'none'; // ✅ Thêm: đổi từ null sang 'none'
            $voucher->value = 0;
            $voucher->min_order_value = 0;
            $voucher->max_discount_value = 0;
        }

        $voucher->save();

        return redirect()->route('vouchers.index')->with('success', 'Tạo voucher thành công!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.pages.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $voucher = Voucher::findOrFail($id);

        $voucher->update([
            'code' => $request->code,
            'title' => $request->title,
            'voucher_type' => $request->voucher_type,
            'value' => $request->value,
            'discount_type' => $request->discount_type,
            'min_order_value' => $request->min_order_value,
            'max_discount_value' => $request->max_discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'limit' => $request->limit,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('vouchers.index')
            ->with('success', 'Cập nhật voucher thành công!');
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
