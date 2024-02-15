@php
$body =  <<<__
公式X（旧Twitter）にてキャンペーン実施中！！
Xアカウント:@CardFesta7627
https://twitter.com/CardFesta7627

配信停止はコチラから↓↓↓↓↓↓↓↓↓
https://cardfesta.jp/settings/email_reception

※このメールは送信専用メールアドレスから配信されています。
このままご返信いただいてもお答えできませんのでご了承ください。

発行・配信元
合同会社Fobees
__;
@endphp


{!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $body) )!!}


