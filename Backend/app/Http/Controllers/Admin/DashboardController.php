<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('permission.404:dashboard')->only('index', 'show');
    }
    public function index(){
        return view('admin.pages.dashboard');
    }
}
