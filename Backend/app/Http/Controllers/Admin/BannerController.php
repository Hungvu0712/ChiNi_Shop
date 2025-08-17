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
    public function __construct(){
        // $this->middleware('permission.404:banner-list')->only('index', 'show');
        // $this->middleware('permission.404:banner-create')->only('create', 'store');
        // $this->middleware('permission.404:banner-edit')->only('edit', 'update');
        // $this->middleware('permission.404:banner-delete')->only('destroy');
        $this->middleware('permission.404:crudbaner')->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    }
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
        $isBannerActive = Banner::where('active', 1)->exists();
        return view('admin.pages.banners.create', compact('isBannerActive'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
{
    $validate = $request->validated();

    // Kiểm tra có ảnh hay không
    if (!$request->hasFile('banner_image')) {
        return redirect()->route('banners.create')->with('error', 'Không có hình ảnh nào!');
    }

    // Upload ảnh lên Cloudinary
    $uploadImage = Cloudinary::upload($request->file('banner_image')->getRealPath(), [
        'folder' => 'banners',
        'overwrite' => true,
    ]);

    $isActive = $request->input('active', 0);
    if ((int)$isActive === 1) {
        // Tắt các banner khác
        Banner::where('active', 1)->update(['active' => 0]);
    }

    // Tạo mới banner
    $banner = Banner::create([
        'title' => $validate['title'],
        'banner_image' => $uploadImage->getSecurePath(),
        'public_banner_image_id' => $uploadImage->getPublicId(),
        'link' => $validate['link'],
        'content' => $validate['content'],
        'active' => $isActive,
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

        $isActive = $request->input('active', 0);

    // Nếu bật banner này, thì tắt các banner khác
    if ((int)$isActive === 1) {
        Banner::where('id', '!=', $banner->id)->update(['active' => 0]);
    }

        $banner->update([
            'title' => $request->title,
            'link' => $request->link,
            'content' => $request->content,
             'active' => $request->input('active', 0),
        ]);

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
