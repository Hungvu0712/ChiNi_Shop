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
