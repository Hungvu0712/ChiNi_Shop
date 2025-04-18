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
    $validated = $request->validated();

    if (Auth::attempt($validated, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Refetch lại user để lấy bản mới nhất từ database
        $user = Auth::user()->fresh();

        // Nếu chưa xác minh email → chuyển hướng đến trang nhắc xác minh
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->withErrors([
                'email' => 'Bạn cần xác minh email trước khi đăng nhập.',
            ]);
        }

        return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Đăng nhập thành công');
    }

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
