<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\StoreRequest;
use App\Http\Requests\Categories\UpdateRequest;

class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('permission.404:category-list')->only('index', 'show');
        $this->middleware('permission.404:category-create')->only('create', 'store');
        $this->middleware('permission.404:category-edit')->only('edit', 'update');
        $this->middleware('permission.404:category-delete')->only('destroy');
    }
    public function index()
    {
        $categories = Category::all();
        return view('admin.pages.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.pages.categories.create');
    }

    public function store(StoreRequest $request)
    {
        $request->validated();

        Category::create($request->only(['name', 'description']));

        return redirect()
            ->route('categories.index')
            ->with('success', 'Thêm thành công');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('admin.pages.categories.edit', compact('category'));
    }

    public function update(UpdateRequest $request, Category $category)
    {
        $request->validated();

        $category->update($request->only(['name', 'description']));

        return redirect()
            ->route('categories.index')
            ->with('success', 'Sửa thành công');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Xóa thành công');
    }
}

