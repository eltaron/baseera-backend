<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إذا لم يكن المستخدم مسجلاً، أو لم تكن رتبته 'student'
        if (!auth()->check() || auth()->user()->role !== 'student') {
            return redirect('/')->with('error', 'عفواً.. هذه المنطقة مخصصة للأبطال الطلاب فقط!');
        }

        return $next($request);
    }
}
