<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeValues\StoreRequest;
use App\Http\Requests\AttributeValues\UpdateRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function __construct(){
        // $this->middleware('permission.404:attributevalue-list')->only('index', 'show');
        // $this->middleware('permission.404:attributevalue-create')->only('create', 'store');
        // $this->middleware('permission.404:attributevalue-edit')->only('edit', 'update');
        // $this->middleware('permission.404:attributevalue-delete')->only('destroy');
        $this->middleware('permission.404:crudattributevalue')->only('index', 'show', 'create', 'store', 'edit', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributeValues = AttributeValue::with('attribute')->get();

        $attributes = Attribute::all();

        return view('admin.pages.attributevalues.index', compact('attributeValues', 'attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = Attribute::all();

        return view('admin.pages.attributevalues.create', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->validated();

        AttributeValue::create($request->all());

        return redirect()->route('attribute_values.index')->with('success', 'Thêm AttributeValue Thành Công');
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
        $attributeValue = AttributeValue::find($id);

        $attributes = Attribute::all();

        return view('admin.pages.attributevalues.edit', compact('attributeValue', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
                $attributeValue = AttributeValue::find($id);

                $attributeValue->update($request->all());

                return redirect()->route('attribute_values.index')->with('success', 'Cập nhật AttributeValue Thành Công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
                $attributeValue = AttributeValue::find($id);

                $attributeValue->delete();

                return redirect()->route('attribute_values.index')->with('success', 'Xóa AttributeValue Thành Công');
    }
}
