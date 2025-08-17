<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attributes\StoreRequest;
use App\Http\Requests\Attributes\UpdateRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(){
        $this->middleware('permission.404:attribute-list')->only('index', 'show');
        $this->middleware('permission.404:attribute-create')->only('create', 'store');
        $this->middleware('permission.404:attribute-edit')->only('edit', 'update');
        $this->middleware('permission.404:attribute-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::all();
        return view('admin.pages.attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->validated();

        Attribute::create($request->all());

        return redirect()->route('attributes.index')->with('success', 'Thêm dữ liệu thành công');
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
        $attribute = Attribute::findOrFail($id);
        return view('admin.pages.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $attribute = Attribute::findOrFail($id);

        $request->validated();

        $attribute->update($request->all());

        return redirect()->route('attributes.index')->with('success', 'Cập nhật dữ liệu thông thống');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::findOrFail($id);

        $attribute->delete();

        return redirect()->route('attributes.index')->with('success', 'Xóa dữ liệu thông thống');
    }
}
