<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectClient
{

    public function handle($request, Closure $next)
    {
        # config からクライアント名を取得
        $client = config('app.client');

        # アプリケーション全体で参照できるようにする app('client')
        app()->instance('client', $client);

        return $next($request);
    }
}
