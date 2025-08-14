<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('permission.404:profile-show')->only('index', 'show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $user = User::with('profile')->findOrFail($id); // Eager load profile
        $roles = $user->roles()->get();

        // Lấy đơn hàng của user
    $orders = \App\Models\Order::where('user_id', $id)
        ->where('order_status', 'Hoàn thành')
        ->orderBy('created_at', 'desc')
        ->get();

    // Tính tổng đơn hàng và tổng chi tiêu
    $totalOrders = $orders->count();
    $totalSpent  = $orders->sum('total');

    return view('admin.pages.profiles.show', compact('user', 'roles', 'orders',
        'totalOrders',
        'totalSpent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
