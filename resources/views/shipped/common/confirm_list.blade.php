<ul class="list-group bg-white">
    <li class="list-group-item p-3">
        <h5>お届け先住所</h5>
        <input type="hidden" name="user_address_id" value="{{ $user_address->id }}">
        <div class="fw-bold">
            {{ $user_address->name }} 様
        </div>
        <div class="fw-bold">
            <span>{{ '〒'.$user_address->postal_code }}</span>
            <span>{{ $user_address->todohuken }}</span>
            <span>{{ $user_address->shikuchoson }}</span>
            <span>{{ $user_address->number }}</span>
        </div>
        <h5 class="mt-3">ご連絡先電話番号</h5>
        <div class="">{{ $user_address->tell }}</div>

        @if( $user_address->email )
            <h5 class="mt-3">ご連絡先メールアドレス</h5>
            <div class="">{{ $user_address->email }}</div>
        @endif
        @if($user_address->size)
            <h5 class="fs-6 mb-0 mt-3">希望の靴サイズ</h5>
            <div class="">
                <span class="fs-4">{{ $user_address->size }}</span>
            </div>
        @endif
        @if($user_address->remarks_text)
            <h5 class="fs-6 mb-0 mt-3">備考欄</h5>
            <div class="">
                {!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $user_address->remarks_text) )!!}
            </div>
        @endif

    </li>
    <li class="list-group-item p-3">
        <h5>利用ポイント</h5>
        <div class="d-flex justify-content-between">
            <span class="form-text">配送料・手数料：</span>
            <span>{{$shipped_point}}pt</span>
        </div>
        <div class="d-flex justify-content-between fs-5 fw-bold">
            <span class="">合計利用ポイント：</span>
            <span class="text-danger">{{$shipped_point}}pt</span>
        </div>
    </li>
    <li class="list-group-item p-3">

        <h5>発送する商品</h5>

        @foreach ($shipped_prizes as $shipped_prize)
            <div class="row p-3">
                <div class="col-3 col-md-2 p-0 pe-2">
                    <div class="">
                        <ratio-image-component
                        style_class="ratio ratio-3x4 rounded-3"
                        url="{{ $shipped_prize->image_path }}" />
                    </div>
                </div>
                <div class="col">
                    <h6 classs="form-text">{{ $shipped_prize->name }}</h6>
                    <div class="form-text">{{ $shipped_prize->code }}</div>
                </div>
                <div class="col-auto text-end">
                    <span class="fs-3">{{ $shipped_prize->count }}</span>点
                </div>
            </div>
        @endforeach


        @foreach ($user_prizes as $user_prize)
            <input type="hidden" name="user_prize_ids[]" value="{{ $user_prize->id }}">
        @endforeach

        <div class="text-end">
            <span class="me-3">合計</span>
            <span class="fs-3">{{ $user_prizes->count() }}</span>点
        </div>

    </li>

    @if( isset($user_shipped) && $user_shipped->shipping_company )
    <li class="list-group-item p-3">
        <h5>荷物を追跡する</h5>

        <div class="row gy-5">
            <div class="col-12 col-md-6">
                <div class="mb-3">
                    <span class="form-text">追跡番号</span>
                    <coppy-button-component copy_word="{{$user_shipped->tracking_code}}"></coppy-button-component>
                </div>
                <div class="mb-3">
                    <span class="form-text">発送業者</span>
                    <div class="">{{$user_shipped->shipping_company->name}}</div>
                </div>

                <a href="{{$user_shipped->shipping_company->url}}"
                class="btn btn-dark border rounded-pill w-100 px-3 mt-4"
                target="_blank"
                >荷物を追跡する<i class="bi bi-box-arrow-up-right ms-2"></i></a>

            </div>
            <div class="col">
                <div class="alert alert-primary border-0" role="alert">
                    <h6 class="fw-bold"
                    ><i class="bi bi-exclamation-circle-fill me-2"
                    ></i>発送業者によって「追跡番号」の表現が異なります</h6>
                    <p class="m-0">
                        ヤマト運輸：「送り状番号」<br>
                        佐川急便：「お問い合せ送り状No.」<br>
                        日本郵便：「お問い合わせ番号」<br>
                        <br>
                        上記の表現と「追跡番号」を確認の上、発送業者サイトへお問い合わせください。
                    </p>
                </div>
            </div>
        </div>

    </li>
    @endif
</ul>
