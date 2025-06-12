<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brands\StoreRequest;
use App\Http\Requests\Brands\UpdateRequest;
use App\Models\Brand;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function __construct(){
        $this->middleware('permission:brand-list')->only('index', 'show');
        $this->middleware('permission:brand-create')->only('create', 'store');
        $this->middleware('permission:brand-edit')->only('edit', 'update');
        $this->middleware('permission:brand-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();

        return view('admin.pages.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $uploadedFileUrl = Cloudinary::upload($request->file('brand_image')->getRealPath(), [
            'folder' => 'brands',
            'overwrite' => true,
        ]);

        Brand::create([
            'name' => $validated['name'],
            'brand_image' => $uploadedFileUrl->getSecurePath(),
            'public_brand_image_id' => $uploadedFileUrl->getPublicId(),
        ]);

        return redirect()->route('brands.index')->with('success', 'Thêm thành công');
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
        $brand = Brand::findOrFail($id);

        return view('admin.pages.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validated();


        $brand->name = $validated['name'] ?? $brand->name;

        if ($request->hasFile('brand_image')) {
            if ($brand->public_brand_image_id) {
                Cloudinary::destroy($brand->public_brand_image_id);
            }

            $uploaded = Cloudinary::upload(
                $request->file('brand_image')->getRealPath(),
                [
                    'folder'     => 'brands',
                    'overwrite'  => true,
                    // 'public_id' => 'tự đặt nếu muốn',
                ]
            );

            $brand->brand_image       = $uploaded->getSecurePath();  // tên cột thật
            $brand->public_brand_image_id = $uploaded->getPublicId();    // tên cột thật
        }

        $brand->save();

        return redirect()
                ->route('brands.index')
                ->with('success', 'Cập nhật thành công');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->public_brand_image_id) {
            Cloudinary::destroy($brand->public_brand_image_id);
        }

        $brand->delete();

        return redirect()
                ->route('brands.index')
                ->with('success', 'Xóa thành công');
    }
}
