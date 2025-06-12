<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('permission:user-list')->only('index', 'show');
        $this->middleware('permission:user-create')->only('create', 'store');
        $this->middleware('permission:user-edit')->only('edit', 'update');
        $this->middleware('permission:user-delete')->only('destroy');
        $this->middleware('permission:user-assign')->only('editRoles', 'updateRoles');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    $users = User::with('roles')->get();

    return view('admin.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $user = User::findOrFail($id);

        $roles = Role::all();

        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.pages.users.edit-roles', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
                $user = User::findOrFail($id);

                if($user->id == '1'){
                    toastr()->error('Không thể cập nhập vai trò của người dùng này');
                    return redirect()->back();
                }

                $request->validate([
            'roles' => 'required|array',
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật vai trò cho người dùng.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
