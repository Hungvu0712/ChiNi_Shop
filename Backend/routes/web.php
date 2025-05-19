<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', [HomeController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');



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


// // Route yêu cầu xác minh email
// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// // Route để gửi lại email xác nhận
// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();

//     return back()->with('status', 'Đã gửi lại email xác nhận!');
// })->middleware('auth')->name('verification.send');

// // Route đăng nhập và xác minh email
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/', [HomeController::class, 'index']);
//     // Thêm các route khác yêu cầu xác minh email ở đây
// });


//Đăng nhập bằng google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
