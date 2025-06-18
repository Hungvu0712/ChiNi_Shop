<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Permission404
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check() || !auth()->user()->can($permission)) {
            return response()->view('404', [], 404); // Trả về view 404.blade.php
        }

        return $next($request);
    }
}
