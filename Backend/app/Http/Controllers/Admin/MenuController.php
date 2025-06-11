<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\StoreRequest;
use App\Http\Requests\Menu\UpdateRequest;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with('children')->get();
        return view('admin.pages.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::all();

        return view('admin.pages.menus.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        Menu::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'url' => $request->url,
            'prant_id' => $request->prant_id,
        ]);

        return redirect()->back()->with('success', 'Thêm menu thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        $menus = Menu::where('id', '!=', $id)->get();

        return view('admin.pages.menus.edit', compact('menu', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $menu->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'url' => $request->url,
            'prant_id' => $request->prant_id,
        ]);

        return redirect()->route('menus.index')->with('success', 'Cập nhật menu thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            return redirect()->back()->with('success', 'Xóa menu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không xác định.');
        }
    }
}
