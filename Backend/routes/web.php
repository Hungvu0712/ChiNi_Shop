<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductAttachmentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Client\AddressController;
use App\Http\Controllers\Client\BannerController as ClientBannerController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ShopController;
use App\Http\Controllers\Client\PostHomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProductReviewController;
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
Route::get('/banner', [ClientBannerController::class, 'show'])->name('client.banner');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:admin|staff'])->name('dashboard');

//QL Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin|staff']], function () {
    //users
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/roles', [UserController::class, 'edit'])->name('users.roles.edit');
    Route::put('/users/{user}/roles', [UserController::class, 'update'])->name('users.roles.update');
    //roles
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{id}/permissions', [RoleController::class, 'editPermissions'])->name('roles.editPermissions');
    Route::put('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
    //permissions
    Route::resource('permissions', PermissionController::class);

    Route::resource('menus', MenuController::class);

    //categories
    Route::resource('categories', CategoryController::class);
    //brands
    Route::resource('brands', BrandController::class);
    //profiles
    Route::get('/profiles/show/{profile}', [AdminProfileController::class, 'show'])->name('profiles.show');

    //post-category
    Route::resource('post-categories', PostCategoryController::class)->parameters(['post-categories' => 'post_category']);

    //products
    Route::resource('products', ProductController::class);
    Route::delete('product-attachments/{id}', [ProductAttachmentController::class, 'destroy'])
        ->name('product-attachments.destroy');
    Route::resource('posts', PostController::class);
    Route::post('/admin/summernote-upload', [PostController::class, 'uploadImageSummernote'])->name('admin.summernote.upload');

    //attributes
    Route::resource('attributes', AttributeController::class);
    //attribute_values
    Route::resource('attribute_values', AttributeValueController::class);
    //banners
    Route::resource('banners', BannerController::class);

});

//Trang 404
Route::get('/404', function () {
    return view('404')->name('pagenotfound');
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

require __DIR__ . '/auth.php';


//Đăng nhập bằng google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);




Route::controller(HomeController::class)->group(function () {
    Route::get('diachi',  'danhsachdiachi')->name('address');
    Route::post('add-address', 'addAddress')->name('add-address');
});
Route::prefix('client')->group(function () {
    Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
    Route::get('/shop', [ShopController::class, 'index'])->name('client.shop.index'); // ✅ Đúng
    Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('client.shop.show');
    Route::post('/review', [ProductReviewController::class, 'store'])->name('client.shop.review');
});
Route::controller(AddressController::class)->group(function () {
    Route::get('diachi' ,  'danhsachdiachi')->name('address');
    Route::post('add-address' , 'addAddress')->name('add-address');
    Route::put('/update-address/{id}', 'update')->name('update-address');
});

Route::controller(PostHomeController::class)->group(function () {
    Route::get('blog' , 'index')->name('blog');
    Route::get('blog-detail/{slug}' , 'show')->name('blog_detail');
});

Route::controller(ReviewController::class)->group(function () {
    Route::get('review', 'index')->name('admin.reviews.index');
    Route::delete('/reviews/{id}',  'destroy')->name('admin.reviews.destroy');
});
