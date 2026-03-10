<div class="">

    <div class="fw-bol mb-3">

        <h6 class="form-text fw-bol m-0">氏名</h6>
        {{ $user_address->name }} 様

    </div>
    <div class="fw-bol mb-3">

        <h6 class="form-text fw-bol m-0">住所</h6>
        <span>{{ '〒'.$user_address->postal_code }}</span><br>
        <span>{{ $user_address->todohuken }}</span>
        <span>{{ $user_address->shikuchoson }}</span>
        <span>{{ $user_address->number }}</span>

    </div>
    <div class="mb-3">

        <h6 class="form-text fw-bol m-0">ご連絡先 電話番号</h6>
        <div class="">{{ $user_address->tell }}</div>

    </div>

    @if( $user_address->email )
        <div class="mb-3">

            <h6 class="form-text fw-bol m-0">ご連絡先 メールアドレス</h6>
            {{ $user_address->email }}

        </div>
    @endif
    @if($user_address->size)
        <div class="mb-3">

            <h6 class="form-text fw-bol m-0">希望の靴サイズ</h6>
            <span class="fs-4">{{ $user_address->size }}</span>

        </div>
    @endif
    @if($user_address->remarks_text)
        <div class="mb-3">
            <h6 class="form-text fw-bol m-0">備考欄</h6>
            {!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $user_address->remarks_text) )!!}
        </div>
    @endif

</div>
