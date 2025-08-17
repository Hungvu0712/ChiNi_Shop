<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        return view('profile.show', [
            'user' => $request->user()->load('profile'),
        ], compact('menus'));
    }

    public function edit(Request $request): View
    {
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        return view('profile.edit', [
            'user' => $request->user()->load('profile'),
        ], compact('menus'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Cập nhật thông tin cơ bản của user
        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Lấy hoặc tạo profile
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        // Cập nhật thông tin profile
        $profile->fill($request->only(['phone', 'address', 'gender', 'birthday']));

        // Xử lý avatar nếu có
        if ($request->hasFile('avatar')) {
            if ($profile->avatar_public_id) {
                Cloudinary::destroy($profile->avatar_public_id);
            }

            $uploaded = Cloudinary::upload($request->file('avatar')->getRealPath(), [
                'folder' => 'avatars',
            ]);

            $profile->avatar = $uploaded->getSecurePath();
            $profile->avatar_public_id = $uploaded->getPublicId();
        }

        $profile->save();

        return Redirect::route('profile.show')->with('success', 'Thay đổi thông tin thành công');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete(); // profile sẽ tự xóa nếu dùng `onDelete('cascade')`

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
