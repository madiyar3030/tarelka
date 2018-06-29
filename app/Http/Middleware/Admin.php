<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->get('vK68TF23TfYKYDBZSCC9') == 1) {
            return $next($request);
        }else{
            return redirect()->route('Login')->with('message', 'Вы не авторизованы!!!');
        }
    }
}
