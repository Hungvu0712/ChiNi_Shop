<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductAttachmentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\ProfileController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:admin'])->name('dashboard');

//QL User
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    //users
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/roles', [UserController::class, 'edit'])->name('users.roles.edit');
    Route::put('/users/{user}/roles', [UserController::class, 'update'])->name('users.roles.update');
    //roles
    Route::resource('roles', RoleController::class);
    //permissions
    Route::resource('permissions', PermissionController::class);
    Route::get('/roles/{id}/permissions', [RoleController::class, 'editPermissions'])->name('roles.editPermissions');
    Route::put('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
    //categories
    Route::resource('categories', CategoryController::class);
    //brands
    Route::resource('brands', BrandController::class);
    //profiles
    Route::get('/profiles/show/{profile}', [AdminProfileController::class, 'show'])->name('profiles.show');
    //products
     Route::resource('products', ProductController::class);
    Route::delete('product-attachments/{id}', [ProductAttachmentController::class, 'destroy'])
        ->name('product-attachments.destroy');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Hiển thị form đổi mật khẩu
    Route::get('/password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');

    // Xử lý việc đổi mật khẩu
    Route::put('/password/change', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

require __DIR__.'/auth.php';


//Đăng nhập bằng google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
// //Danh muc
// Route::group([
//     'prefix'     => 'admin',
//     'middleware' => ['auth', 'role:admin']
// ], function () {
//     Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');

//     Route::resource('roles',        RoleController::class);
//     Route::resource('permissions',  PermissionController::class);
//     Route::resource('categories',   CategoryController::class);
// });

