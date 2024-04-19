<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [

        # //決済完了ウェブホック //https://cardfesta.jp/stripe/webhook
        'stripe/webhook',

        # サブスク決済完了ウェブホック //https://cardfesta.jp/stripe/subscription/webhook
        'stripe/subscription/webhook',


    ];
}
