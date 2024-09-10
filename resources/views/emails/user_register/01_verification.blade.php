<h3>
    ≪{{ env('APP_NAME') }}≫をご利用いただきありがとうございます。
</h3>
<br>
会員登録は、以下の認証番号が必要です。<br>
認証番号{{ $verification_code }}<br>
<br>
引き続き、会員登録のご入力をお願い致します。<br>
<br>
<br>
<!-- 共通署名 -->
@include('emails._signature')

