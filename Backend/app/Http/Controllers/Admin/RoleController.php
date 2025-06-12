<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(){
        $this->middleware('permission:role-list')->only('index', 'show');
        $this->middleware('permission:role-create')->only('create', 'store');
        $this->middleware('permission:role-edit')->only('edit', 'update');
        $this->middleware('permission:role-delete')->only('destroy');
        $this->middleware('permission:role-assign')->only('editPermissions', 'updatePermissions');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->validated();

        $role = Role::create($request->all());


        if($role){
            toastr()->success('Khởi tạo vai trò thành công');
            return redirect()->route('roles.index');
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
        $roleID = Role::findOrFail($id);

        return view('admin.pages.roles.edit', compact('roleID'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $roleID = Role::findOrFail($id);

        $roleID->update($request->all());

        if($roleID){
            toastr()->success('Cập nhật vai trò thành công');

            return redirect()->route('roles.index');
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
        $roleID = Role::findOrFail($id);

        if($roleID->name == 'admin'){
            toastr()->error('Không thể xóa vai trò admin');

            return redirect()->back();
        }

        $roleID->delete();

        if($roleID){
            toastr()->success('Xóa thông tin thành công');

            return redirect()->route('roles.index');
        }else{
            toastr()->error('Vui lòng thử lại');

            return redirect()->back();
        }
    }

    public function editPermissions($id)
{
    $role = Role::findOrFail($id);
    $permissions = Permission::all();
    $rolePermissions = $role->permissions->pluck('id')->toArray();

    return view('admin.pages.roles.edit-permissions', compact('role', 'permissions', 'rolePermissions'));
}


public function updatePermissions(Request $request, $id)
{
    $role = Role::findOrFail($id);

    // Lấy mảng ID từ form
    $permissionIds = $request->input('permissions', []);

    // Chuyển ID thành name
    $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

    // Gán permission bằng tên
    $role->syncPermissions($permissionNames);

    return redirect()->route('roles.index')->with('success', 'Gán quyền thành công cho vai trò');
}

}
