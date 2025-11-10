
@php
$app_name     =  env('APP_NAME');
$company_name = config('app.company_name');
$r_contact    = route('contact');


$body = <<<__EOT__
このメールは≪{$app_name}≫の会員登録お手続きをされた方に自動送信しています。
このメールに心当たりのない場合や、ご不明な点がある場合は、下記お問い合わせ先へご連絡ください。
{$r_contact}
（このメールへの返信はできません）

発行・配信元
{$company_name}
__EOT__;
@endphp


{!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $body) )!!}


