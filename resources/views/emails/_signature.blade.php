@php
$head = \App\Models\Text::getEmailSignatureHead();
$body = \App\Models\Text::getEmailSignature();
@endphp


{!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $head) )!!}

{!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $body) )!!}
