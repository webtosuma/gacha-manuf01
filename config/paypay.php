<?php

/*
|--------------------------------------------------------------------------
| PayPay
|--------------------------------------------------------------------------
|
*/
return [


    'api_key'     => env('PAYPAY_API_KEY'),

    'api_secret'  => env('PAYPAY_API_SECRET'),

    'merchant_id' => env('PAYPAY_MERCHANT_ID'),

    'environment' => (bool) env('PAYPAY_ENVIRONMENT', false),
];
