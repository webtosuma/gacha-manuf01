<?php
return [

    'public_key'      => env('STRIPE_KEY'),
    'secret_key'      => env('STRIPE_SECRET'),
    'endpoint_secret' => env('STRIPE_ENDPOINT_SECRET'),
    'subscription_endpoint_secret' => env('STRIPE_SUBSCRIPTION_ENDPOINT_SECRET'), //サブスク用エンドポイント
    

];
