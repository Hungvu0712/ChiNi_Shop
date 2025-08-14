<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCategories\StoreRequest;
use App\Http\Requests\PostCategories\UpdateRequest;
use App\Models\PostCategory;

class PostCategoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission.404:postcategory-list')->only('index', 'show');
        // $this->middleware('permission.404:postcategory-create')->only('create', 'store');
        // $this->middleware('permission.404:postcategory-edit')->only('edit', 'update');
        // $this->middleware('permission.404:postcategory-delete')->only('destroy');
        $this->middleware('permission.404:crudpostcategory')->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postCategories = PostCategory::all();
        return view('admin.pages.postcategories.index', compact('postCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.pages.postcategories.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $slug = \Str::slug($validated['name']);

        PostCategory::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('post-categories.index')->with('success', 'Thêm danh mục bài viết thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $category = PostCategory::where('slug', $slug)->firstOrFail();

        return view('admin.pages.postcategories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $slug)
    {
        $category = PostCategory::where('slug', $slug)->firstOrFail();

        $category->update([
            'name' => $request->name,
            'slug' => $request->name !== $category->name
                ? \Str::slug($request->name)
                : $category->slug,
            'description' => $request->description,
        ]);

        return redirect()->route('post-categories.index')->with('success', 'Cập nhật danh mục bài viết thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $category = PostCategory::where('slug', $slug)->firstOrFail();

        $category->delete();

        return redirect()->route('post-categories.index')->with('success', 'Xóa danh mục bài viết thành công.');
    }
}
