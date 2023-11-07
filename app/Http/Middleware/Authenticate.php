<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            # 前ページのURLをセッションに保存
            $before_admin_url = $request->path();
            $request->session()->put( 'before_admin_url', $before_admin_url);


            return route('require_login');
            // return route('login');
        }
    }
}
