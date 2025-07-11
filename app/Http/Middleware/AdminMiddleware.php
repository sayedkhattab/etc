<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من أن المستخدم مسجل الدخول كمسؤول
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        // التحقق من أن حساب المسؤول نشط
        $admin = Auth::guard('admin')->user();
        if ($admin->status !== 'active') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة.');
        }
        
        return $next($request);
    }
}
