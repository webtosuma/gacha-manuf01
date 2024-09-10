<h3>
    いつも≪{{ env('APP_NAME') }}≫をご利用いただきありがとうございます。
</h3>
お客様のお手続きにより、ポイントのご購入が完了いたしましたのでお知らせします。<br>
<br><br>
<div>---------- ご購入内容 ----------</div>
<br>
<div>
    {{$user->name}}様
</div>
<br>
<div>
    購入金額：{{$point_sail->price}}円
</div>
<br>
<div>
    購入ポイント：{{$point_sail->value}}pt
</div>
<br>
<br>
<div>---------------------------------------</div>
<br>
ご注文内容に誤りがある場合は、速やかにお知らせください。<br>
<a href="{{ route('contact') }}">{{ route('contact') }}</a>
<br>
引き続き、{{ env('APP_NAME') }}をご利用ください。<br>
<br>
<br>
<!-- 共通署名 -->
@include('emails._signature')
