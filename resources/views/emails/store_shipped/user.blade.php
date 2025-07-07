いつも≪{{ env('APP_NAME') }}≫をご利用いただきありがとうございます。<br>
ご注文いただいた商品の発送が完了いたしましたことをお知らせいたします。<br>
<br><br>
<div>--------------- 発送情報 ---------------</div>
<br>

<div>
    <strong>発送コード</strong>
    <br>
    {{ $store_history->code}}
</div>
<br>

<div>
    <strong>ご注文日時</strong>
    <br>
    {{ $store_history->done_at->format('Y年m月d日 H:i')}}
</div>
<br>

<div>
    <strong>お届け先住所</strong>
    <br>
    @php $user_address = $store_history->address; @endphp
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
    @foreach ($store_keeps as $store_keep)
        <div>
            {{ $store_keep->done_store_item_name }} ...
            <span>{{ $store_keep->count }}</span>点
        </div>
        <br>
    @endforeach
    合計<span>{{ number_format( $store_history->sumItemsCount() ) }}</span>点
</div>
<br>
<div>---------------------------------------</div>
<br>
<br>
これからも、{{ env('APP_NAME') }}をよろしくお願いいたします。。<br>
<br>
<br>
<!-- 共通署名 -->
@include('emails._signature')
