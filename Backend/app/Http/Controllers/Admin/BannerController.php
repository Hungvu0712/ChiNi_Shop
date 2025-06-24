<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
        return view('admin.pages.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validate = $request->validated();

        //lưu ảnh lên cloud
            $uploadImage = Cloudinary::upload($request->file('banner_image')->getRealPath(), [
                'folder' => 'banners',
                'overwrite' => true,
            ]);

            if(!$request->hasFile('banner_image')){
                return redirect()->route('banners.create')->with('error', 'Không có hình ảnh nào!');
            }

            $banner = Banner::create([
                'title' => $validate['title'],
                'banner_image' => $uploadImage->getSecurePath(),
                'public_banner_image_id' => $uploadImage->getPublicId(),
                'link' => $validate['link'],
                'content' => $validate['content'],
                'active' => $request->active ? 1 : 0,
            ]);

            return redirect()->route('banners.index')->with('success', 'Khởi tạo thành công');
            
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
        $banner = Banner::findOrFail($id);
        return view('admin.pages.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $validate = $request->validated();

        if($request->hasFile('banner_image')){
            if($banner->public_banner_image_id){
                Cloudinary::destroy($banner->public_banner_image_id);  // xóa ảnh
            }

            $upload = Cloudinary::upload(
                $request->file('banner_image')->getRealPath(),
                [
                    'folder' => 'banners',
                    'overwrite' => true,
                ]
            );

            $banner->banner_image = $upload->getSecurePath();
            $banner->public_banner_image_id = $upload->getPublicId();
        }

        $banner->save();

        return redirect()->route('banners.index')->with('success', 'Sửa thông tin thành công');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);

        //xóa title đó xóa cả ảnh trên cloud của id đó
        if($banner->public_banner_image_id){
            Cloudinary::destroy($banner->public_banner_image_id);  // xóa ảnh
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Xóa thành công');

    }
}
