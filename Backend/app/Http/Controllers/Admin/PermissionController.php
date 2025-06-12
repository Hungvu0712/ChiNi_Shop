<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(){
        $this->middleware('permission:permssion-list')->only('index', 'show');
        $this->middleware('permission:permssion-create')->only('create', 'store');
        $this->middleware('permission:permssion-edit')->only('edit', 'update');
        $this->middleware('permission:permssion-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();

        return view ('admin.pages.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->validated();

        $permission = Permission::create($request->all());

        if($permission){
            toastr()->success('Khởi tạo quyền thành công');
            return redirect()->route('permissions.index');
        }else{
            toastr()->error('Vui lòng thử lại');
            return redirect()->back();
        }
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
        $permissionID = Permission::findOrFail($id);

        return view('admin.pages.permissions.edit', compact('permissionID'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $permissionID = Permission::findOrFail($id);

        $permissionID->update($request->all());

        if($permissionID){
            toastr()->success('Cập nhật quyền thành công');
            return redirect()->route('permissions.index');
        }else{
            toastr()->error('Vui lòng thử lại');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permissionID = Permission::findOrFail($id);

        $permissionID->delete();

        if($permissionID){
            toastr()->success('Xóa quyền thành công');
            return redirect()->route('permissions.index');
        }else{
            toastr()->error('Vui lòng thử lại');
            return redirect()->back();
        }
    }
}
