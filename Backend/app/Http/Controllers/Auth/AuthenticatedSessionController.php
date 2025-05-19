<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    // Xác thực dữ liệu đầu vào
    $validated = $request->validated();

    // Thực hiện đăng nhập với thông tin đã xác thực
    if (Auth::attempt($validated, $request->boolean('remember'))) {
        $request->session()->regenerate(); // Giữ phiên đăng nhập

        // Lấy thông tin người dùng mới nhất từ cơ sở dữ liệu
        $user = Auth::user()->fresh();

        return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Đăng nhập thành công');
    }

    // Nếu thông tin đăng nhập không đúng, quay lại trang đăng nhập và thông báo lỗi
    return back()->withErrors([
        'email' => 'Thông tin đăng nhập không chính xác.',
    ]);
}



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
