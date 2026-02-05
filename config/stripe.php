<?php
return [

    'public_key'      => env('STRIPE_KEY'),
    'secret_key'      => env('STRIPE_SECRET'),
    'endpoint_secret' => env('STRIPE_ENDPOINT_SECRET'),
    'subscription_endpoint_secret' => env('STRIPE_SUBSCRIPTION_ENDPOINT_SECRET'), //サブスク用エンドポイント


    /* 決済の種類 */
    'payment_method_types' => [

        'card'             => true,
        'jcb'             => env('STRIPE_PAYMENTTYPES_JCB'     ,false),

        'applepay'         => env('STRIPE_PAYMENTTYPES_APPLEPAY' ,false),
        'googlepay'        => env('STRIPE_PAYMENTTYPES_GOOGLEPAY',false),
        'paypay'           => env('STRIPE_PAYMENTTYPES_PAYPAY'   ,false),

        'konbini'          => env('STRIPE_PAYMENTTYPES_KONBINI'  ,false),
        'customer_balance' => env('STRIPE_PAYMENTTYPES_BANK'     ,false),

    ],
];
