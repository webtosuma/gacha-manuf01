いつも≪{{ env('APP_NAME') }}≫をご利用いただきありがとうございます。<br>
ご注文いただいた商品の発送が完了いたしましたことをお知らせいたします。<br>
<br><br>
<div>--------------- 発送情報 ---------------</div>
<br>

<div>
    <strong>発送コード</strong>
    <br>
    {{ $user_shipped->code}}
</div>
<br>

<div>
    <strong>ご注文日時</strong>
    <br>
    {{ $user_shipped->created_at->format('Y年m月d日 H:i')}}
</div>
<br>

<div>
    <strong>お届け先住所</strong>
    <br>
    @php $user_address = $user_shipped->user_address; @endphp
    <div>
        <span>{{ '〒'.$user_address->postal_code }}</span><br>
        <span>{{ $user_address->todohuken }}</span>
        <span>{{ $user_address->shikuchoson }}</span>
        <span>{{ $user_address->number }}</span>
    </div>
    <div>
        {{ $user_address->name }} 様
    </div>
</div>
<br>

<div>
    <strong>発送商品情報</strong>
    <br>
    @foreach ($shipped_prizes as $shipped_prize)
        <div>
            {{ $shipped_prize->name }} ...
            <span>{{ $shipped_prize->count }}</span>点
        </div>
        <br>
    @endforeach
    合計<span>{{ $user_shipped->user_prizes->count() }}</span>点
</div>
<br>
<div>---------------------------------------</div>
<br>
<br>
引き続き、{{ env('APP_NAME') }}をご利用ください。<br>
<br>
<br>
<!-- 共通署名 -->
@include('emails._signature')
